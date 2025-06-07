<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\Soal;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class DetailUjianSantri extends Component
{
    use WithPagination;

    #[Title('Detail Ujian Santri')]

    public $santriId;
    public $santri;
    public $ujianList;
    public $selectedUjian;
    public $hasilUjian;
    public $jawabanUjian = [];
    public $nilaiEssay = []; // Properti untuk nilai sementara essay
    public $totalNilai = 0;

    public function mount($id)
    {
        $this->santriId = $id;
        $this->santri = PendaftaranSantri::findOrFail($id);
        $this->loadUjianList();
    }

    public function loadUjianList()
    {
        $this->ujianList = Ujian::with(['hasilUjians' => function($query) {
            $query->where('santri_id', $this->santriId);
        }])->get();
    }

    public function viewSoal($ujianId)
    {
        $this->selectedUjian = Ujian::with(['soals'])->findOrFail($ujianId);
        $this->hasilUjian = HasilUjian::where('santri_id', $this->santriId)
            ->where('ujian_id', $ujianId)
            ->first();

        if ($this->hasilUjian) {
            $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)
                ->get()
                ->map(function ($jawaban) {
                    $soal = Soal::find($jawaban->soal_id);
                    if ($soal && $soal->tipe_soal === 'pg' && !in_array($jawaban->jawaban, ['A', 'B', 'C', 'D'])) {
                        $jawaban->jawaban = 'A';
                    }
                    return $jawaban;
                })
                ->keyBy('soal_id')
                ->toArray();
            
            // Inisialisasi nilaiEssay untuk soal essay
            foreach ($this->selectedUjian->soals as $soal) {
                if ($soal->tipe_soal === 'essay') {
                    $this->nilaiEssay[$soal->id] = isset($this->jawabanUjian[$soal->id]) ? $this->jawabanUjian[$soal->id]['nilai'] : 0;
                }
            }
            
            $this->hitungTotalNilai();
        }
    }

    public function simpanNilai($soalId, $nilai)
    {
        if (!$this->hasilUjian) {
            return;
        }

        $nilai = (int) $nilai; // Pastikan nilai adalah integer
        $soal = Soal::find($soalId);
        if ($soal && $nilai >= 0 && $nilai <= $soal->poin) {
            $jawaban = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)
                ->where('soal_id', $soalId)
                ->first();
            if ($jawaban) {
                $jawaban->update(['nilai' => $nilai]);
                $this->jawabanUjian[$soalId]['nilai'] = $nilai;
                $this->nilaiEssay[$soalId] = $nilai;
                $this->hitungTotalNilai();
            }
        }
    }

    public function hitungTotalNilai()
    {
        $this->totalNilai = collect($this->jawabanUjian)->sum('nilai');
        
        if ($this->hasilUjian) {
            $this->hasilUjian->update([
                'nilai_akhir' => $this->totalNilai
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.psb.detail-ujian-santri', [
            'santri' => $this->santri,
            'ujianList' => $this->ujianList
        ]);
    }
}