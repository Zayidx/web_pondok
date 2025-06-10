<?php

namespace App\Livewire\Psb;

use Livewire\Component;
use App\Models\PSB\Soal;
use App\Models\PSB\JawabanUjian;
use App\Models\PSB\Ujian;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use Livewire\Attributes\Title;

#[Title('Detail Soal Ujian')]
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
        $this->loadData();
    }

    public function loadData()
    {
        // Load soal ujian with jawaban santri
        $this->soalUjian = Soal::where('ujian_id', $this->ujianId)
            ->orderBy('tipe_soal') // PG first, then Essay
            ->get();

        $this->hasilUjian = HasilUjian::where([
            'santri_id' => $this->santriId,
            'ujian_id' => $this->ujianId
        ])->first();

        if ($this->hasilUjian) {
            $this->jawabanUjian = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)
                ->get()
                ->keyBy('soal_id');
        }

        // Calculate total poin
        $this->calculateTotalPoin();
    }

    public function calculateTotalPoin()
    {
        $this->totalPoin = 0;
        foreach ($this->soalUjian as $soal) {
            $jawaban = $this->jawabanUjian->get($soal->id);
            if ($jawaban) {
                if ($soal->tipe_soal === 'pg') {
                    // For PG, check if answer matches key
                    if ($jawaban->jawaban === $soal->kunci_jawaban) {
                        $this->totalPoin += $soal->poin;
                    }
                } else {
                    // For essay, use the saved points
                    $this->totalPoin += $this->poinEssay[$soal->id] ?? 0;
                }
            }
        }
    }

    public function savePoinEssay($soalId, $poin)
    {
        $this->poinEssay[$soalId] = $poin;
        $this->calculateTotalPoin();
    }

    public function saveNilai()
    {
        foreach ($this->poinEssay as $soalId => $poin) {
            $jawaban = $this->jawabanUjian->get($soalId);
            if ($jawaban) {
                $jawaban->update([
                    'nilai' => $poin
                ]);
            }
        }

        session()->flash('success', 'Nilai berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.psb.detail-soal-santri');
    }
} 