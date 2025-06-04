<?php

namespace App\Livewire\SantriPPDB;

use App\Models\JawabanSantri;
use App\Models\Soal;
use App\Models\Ujian;
use Livewire\Attributes\Title;
use Livewire\Component;

class UjianSantri extends Component
{
    #[Title('Halaman Ujian Santri')]

    public $ujianId;
    public $soalId;
    public $jawaban;
    public $currentSoalIndex = 0;
    public $soals;

    public function mount($ujianId)
    {
        $this->ujianId = $ujianId;
        $this->soals = Soal::where('ujian_id', $this->ujianId)->get();
        $this->soalId = $this->soals->isNotEmpty() ? $this->soals[0]->id : null;
    }

    public function submitJawaban()
    {
        try {
            $this->validate([
                'jawaban' => 'required',
            ]);

            JawabanSantri::updateOrCreate(
                [
                    'santri_id' => auth()->guard('santri')->user()->id, // Menggunakan guard santri
                    'ujian_id' => $this->ujianId,
                    'soal_id' => $this->soalId,
                ],
                ['jawaban' => $this->jawaban]
            );

            // Pindah ke soal berikutnya
            $this->currentSoalIndex++;
            if ($this->currentSoalIndex < $this->soals->count()) {
                $this->soalId = $this->soals[$this->currentSoalIndex]->id;
                $this->jawaban = '';
            } else {
                // Update hasil ujian
                $totalSkor = JawabanSantri::where('santri_id', auth()->guard('santri')->user()->id)
                    ->where('ujian_id', $this->ujianId)
                    ->whereHas('soal', function ($query) {
                        $query->where('tipe_soal', 'pg')->whereColumn('jawaban', 'soals.jawaban_benar');
                    })
                    ->sum('soals.bobot_nilai');

                \App\Models\HasilUjian::updateOrCreate(
                    ['santri_id' => auth()->guard('santri')->user()->id, 'ujian_id' => $this->ujianId],
                    ['total_skor' => $totalSkor, 'status' => 'menunggu']
                );

                session()->flash('success', 'Ujian selesai! Menunggu penilaian essay.');
                return redirect()->route('santri.dashboard');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $soal = $this->soals->get($this->currentSoalIndex);
        return view('livewire.santri-ppdb.ujian-santri', compact('soal'));
    }
}