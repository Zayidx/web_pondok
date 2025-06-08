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
    public $nilaiEssay = [];
    public $totalNilai = 0;
    public $totalNilaiPerUjian = [];

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

        foreach ($this->ujianList as $ujian) {
            $hasilUjian = $ujian->hasilUjians->first();
            $this->totalNilaiPerUjian[$ujian->id] = $hasilUjian && $hasilUjian->status === 'selesai' 
                ? ($hasilUjian->nilai_akhir ?? 0) 
                : 0;
        }
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

            $this->nilaiEssay = [];
            foreach ($this->selectedUjian->soals as $soal) {
                if ($soal->tipe_soal === 'essay') {
                    $this->nilaiEssay[$soal->id] = isset($this->jawabanUjian[$soal->id]) 
                        ? ($this->jawabanUjian[$soal->id]['nilai'] ?? 0)
                        : 0;
                }
            }
            
            $this->hitungTotalNilai($ujianId);
        }
    }

    public function simpanNilai($soalId, $nilai)
    {
        if (!$this->hasilUjian) {
            return;
        }

        $jawaban = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)
            ->where('soal_id', $soalId)
            ->first();

        if ($jawaban) {
            $jawaban->update(['nilai' => $nilai]);
            $this->jawabanUjian[$soalId]['nilai'] = $nilai;
            $this->nilaiEssay[$soalId] = $nilai;
            $this->hitungTotalNilai($this->selectedUjian->id);
        }
    }

    public function perbaruiSemuaNilai()
    {
        if (!$this->hasilUjian) {
            return;
        }

        foreach ($this->nilaiEssay as $soalId => $nilai) {
            $jawaban = JawabanUjian::where('hasil_ujian_id', $this->hasilUjian->id)
                ->where('soal_id', $soalId)
                ->first();

            if ($jawaban) {
                $soal = Soal::find($soalId);
                $nilai = min(max((int)$nilai, 0), $soal->poin);
                $jawaban->update(['nilai' => $nilai]);
                $this->jawabanUjian[$soalId]['nilai'] = $nilai;
            }
        }

        $this->hitungTotalNilai($this->selectedUjian->id);
        session()->flash('message', 'Semua nilai essay berhasil diperbarui.');
    }

    public function hitungTotalNilai($ujianId)
    {
        $total = 0;
        if ($this->selectedUjian && $this->selectedUjian->id == $ujianId) {
            foreach ($this->selectedUjian->soals as $soal) {
                if (isset($this->jawabanUjian[$soal->id])) {
                    if ($soal->tipe_soal === 'pg') {
                        $total += $this->jawabanUjian[$soal->id]['jawaban'] == $soal->kunci_jawaban ? $soal->poin : 0;
                    } else {
                        $total += isset($this->nilaiEssay[$soal->id]) ? (int)$this->nilaiEssay[$soal->id] : ($this->jawabanUjian[$soal->id]['nilai'] ?? 0);
                    }
                }
            }
        }

        $this->totalNilai = $total;
        $this->totalNilaiPerUjian[$ujianId] = $total;

        if ($this->hasilUjian && $this->hasilUjian->ujian_id == $ujianId) {
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