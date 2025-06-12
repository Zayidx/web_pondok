<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\Soal;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection; // <-- Add this use statement

#[Title('Detail & Penilaian Ujian Santri')]
class DetailSoalSantri extends Component
{
    public $ujianId;
    public $santriId;
    public $ujian;
    public $santri;
    public $soalUjian;
    public Collection $jawabanUjian; // <-- Type-hint as Collection for clarity and safety
    public $hasilUjian;
    public $poinEssay = [];
    public $totalPoin = 0;

    public function mount($ujianId, $santriId)
    {
        $this->ujianId = $ujianId;
        $this->santriId = $santriId;
        $this->santri = PendaftaranSantri::findOrFail($santriId);
        $this->ujian = Ujian::findOrFail($ujianId);
        $this->loadData();
    }

    public function loadData()
    {
        $this->soalUjian = Soal::where('ujian_id', $this->ujianId)->orderBy('id', 'asc')->get();
        $this->hasilUjian = HasilUjian::where(['santri_id' => $this->santriId, 'ujian_id' => $this->ujianId])->first();

        // Initialize $jawabanUjian as an empty collection if hasilUjian is null
        if ($this->hasilUjian) {
            $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get()->keyBy('soal_id');
            foreach ($this->soalUjian as $soal) {
                if ($soal->tipe_soal === 'essay' && isset($this->jawabanUjian[$soal->id])) {
                    $this->poinEssay[$soal->id] = $this->jawabanUjian[$soal->id]->nilai ?? 0;
                }
            }
        } else {
            $this->jawabanUjian = new Collection(); // Initialize as an empty collection
        }
        $this->calculateTotalPoin();
    }

    public function calculateTotalPoin()
    {
        $this->totalPoin = 0;
        // No need for if (!$this->jawabanUjian) return; because it's always a Collection now
        // if (!$this->jawabanUjian) return; // This line can be removed

        foreach ($this->soalUjian as $soal) {
            $jawabanSantri = $this->jawabanUjian->get($soal->id);
            if ($jawabanSantri) {
                if ($soal->tipe_soal === 'pg') {
                    $jawabanSantriHuruf = is_numeric($jawabanSantri->jawaban) ? chr((int)$jawabanSantri->jawaban + 65) : $jawabanSantri->jawaban;
                    $kunciJawabanHuruf = is_numeric($soal->kunci_jawaban) ? chr((int)$soal->kunci_jawaban + 65) : $soal->kunci_jawaban;

                    if ($jawabanSantriHuruf === $kunciJawabanHuruf) {
                        $this->totalPoin += $soal->poin;
                    }
                } else {
                    $this->totalPoin += (float)($this->poinEssay[$soal->id] ?? 0);
                }
            }
        }
    }

    public function updatedPoinEssay()
    {
        $this->calculateTotalPoin();
    }

    public function saveNilai()
    {
        if (!$this->hasilUjian) {
            session()->flash('error', 'Data hasil ujian tidak ditemukan. Tidak dapat menyimpan nilai.');
            return;
        }

        DB::transaction(function () {
            Log::info('Attempting to save essay points for hasil_ujian_id: ' . $this->hasilUjian->id);
            foreach ($this->poinEssay as $soalId => $poin) {
                // Ensure $poin is cast to a number (float) before saving
                JawabanUjian::updateOrCreate(
                    ['hasil_ujian_id' => $this->hasilUjian->id, 'soal_id' => $soalId],
                    ['nilai' => (float)$poin, 'jawaban' => $this->jawabanUjian->get($soalId)?->jawaban ?? '']
                );
                Log::info("Saved soal_id {$soalId} with nilai {$poin}");
            }
    
            // Re-fetch answers to ensure the most up-to-date 'nilai' is used for total calculation
            $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get()->keyBy('soal_id');
            $this->calculateTotalPoin();
    
            Log::info('Updating HasilUjian nilai_akhir: ' . $this->totalPoin);
            $this->hasilUjian->update(['nilai_akhir' => $this->totalPoin]);
    
            $semuaHasilUjianSelesai = HasilUjian::where('santri_id', $this->santriId)
                                          ->where('status', 'selesai')
                                          ->get();

            $rataRataBaru = $semuaHasilUjianSelesai->avg('nilai_akhir') ?? 0;
            $totalNilaiKeseluruhan = $semuaHasilUjianSelesai->sum('nilai_akhir'); 
            
            Log::info('Updating Santri rata_rata_ujian: ' . $rataRataBaru . ' and total_nilai_semua_ujian: ' . $totalNilaiKeseluruhan);
            $this->santri->update([
                'rata_rata_ujian'         => $rataRataBaru,
                'total_nilai_semua_ujian' => $totalNilaiKeseluruhan,
            ]);
    
            Log::info('Transaction committed successfully.');
        });

        session()->flash('success', 'Semua nilai berhasil disimpan!');
        $this->dispatch('nilai-tersimpan');
        $this->santri->refresh();
        $this->hasilUjian->refresh();
    }

    public function render()
    {
        return view('livewire.admin.psb.detail-soal-santri');
    }
}