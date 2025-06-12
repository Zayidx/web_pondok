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
use Illuminate\Support\Facades\Log; // Pastikan ini di-import di atas

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
    
            // Tambahkan logging di sini
            if ($hasilUjian) {
                Log::info("Ujian ID: {$ujian->id}, HasilUjian Status: {$hasilUjian->status}, Nilai Akhir: {$hasilUjian->nilai_akhir}");
            } else {
                Log::info("Ujian ID: {$ujian->id}, Tidak ada HasilUjian ditemukan.");
            }
    
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