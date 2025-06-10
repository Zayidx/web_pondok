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

    public function render()
    {
        return view('livewire.admin.psb.detail-ujian-santri', [
            'santri' => $this->santri,
            'ujianList' => $this->ujianList
        ]);
    }
}