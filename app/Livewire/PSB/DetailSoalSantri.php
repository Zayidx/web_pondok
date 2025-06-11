<?php

namespace App\Livewire\PSB;

use Livewire\Component;
use App\Models\PSB\Soal;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use Illuminate\Support\Facades\DB; // Import DB untuk Transaction
use Livewire\Attributes\Title;

#[Title('Detail & Penilaian Ujian Santri')]
class DetailSoalSantri extends Component
{
    public $ujianId;
    public $santriId;
    public $ujian;
    public $santri;
    public $soalUjian;
    public $jawabanUjian;
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

        if ($this->hasilUjian) {
            $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get()->keyBy('soal_id');
            foreach ($this->soalUjian as $soal) {
                if ($soal->tipe_soal === 'essay' && isset($this->jawabanUjian[$soal->id])) {
                    $this->poinEssay[$soal->id] = $this->jawabanUjian[$soal->id]->nilai ?? 0;
                }
            }
        }
        $this->calculateTotalPoin();
    }

    public function calculateTotalPoin()
    {
        $this->totalPoin = 0;
        if (!$this->jawabanUjian) return;

        foreach ($this->soalUjian as $soal) {
            $jawabanSantri = $this->jawabanUjian->get($soal->id);
            if ($jawabanSantri) {
                if ($soal->tipe_soal === 'pg') {
                    $jawabanSantriHuruf = is_numeric($jawabanSantri->jawaban) ? chr((int)$jawabanSantri->jawaban + 65) : $jawabanSantri->jawaban;
                    if ($jawabanSantriHuruf === $soal->kunci_jawaban) {
                        $this->totalPoin += $soal->poin;
                    }
                } else {
                    $this->totalPoin += (int)($this->poinEssay[$soal->id] ?? 0);
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
            session()->flash('error', 'Data hasil ujian tidak ditemukan.');
            return;
        }

        DB::transaction(function () {
            // 1. Simpan nilai per soal essay
            foreach ($this->poinEssay as $soalId => $poin) {
                JawabanUjian::updateOrCreate(
                    ['hasil_ujian_id' => $this->hasilUjian->id, 'soal_id' => $soalId],
                    ['nilai' => $poin, 'jawaban' => $this->jawabanUjian->get($soalId)?->jawaban ?? '']
                );
            }
            
            // Muat ulang data jawaban untuk kalkulasi final
            $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)->get()->keyBy('soal_id');
            $this->calculateTotalPoin();
            
            // 2. Simpan nilai akhir untuk ujian INI
            $this->hasilUjian->update(['nilai_akhir' => $this->totalPoin]);
            
            // =================================================================
            // **LOGIKA BARU DIMULAI DI SINI**
            // =================================================================
            // 3. Ambil SEMUA hasil ujian santri yang sudah selesai
            $semuaHasilUjian = HasilUjian::where('santri_id', $this->santriId)
                                          ->where('status', 'selesai')
                                          ->get();

            // 4. Hitung nilai rata-rata baru
            $rataRataBaru = $semuaHasilUjian->avg('nilai_akhir') ?? 0;

            // 5. Simpan nilai rata-rata ke tabel pendaftaran santri
            $this->santri->update(['rata_rata_ujian' => $rataRataBaru]);
            // =================================================================
            // **LOGIKA BARU SELESAI**
            // =================================================================

        });

        session()->flash('success', 'Semua nilai berhasil disimpan!');
        $this->dispatch('nilai-tersimpan');
        // Muat ulang data santri untuk me-refresh tampilan rata-rata
        $this->santri->refresh();
    }

    public function render()
    {
        return view('livewire.psb.detail-soal-santri');
    }
}