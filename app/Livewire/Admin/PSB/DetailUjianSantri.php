<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
use App\Models\PSB\Ujian;
use App\Models\PSB\JawabanUjian; // Pastikan ini di-import
use App\Models\PSB\Soal;       // Pastikan ini di-import
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;

class DetailUjianSantri extends Component
{
    use WithPagination;

    #[Title('Detail Ujian Santri')]

    public $santriId;
    public $santri;
    public $ujianList;
    public $totalNilaiPerUjian = []; // Array untuk menyimpan total nilai per ujian

    public function mount($id)
    {
        $this->santriId = $id;
        $this->santri = PendaftaranSantri::findOrFail($id);
        $this->loadUjianList();
    }

    public function loadUjianList()
    {
        // Mengambil semua ujian dan hasil ujian terkait untuk santri ini
        $this->ujianList = Ujian::with(['hasilUjians' => function($query) {
            $query->where('santri_id', $this->santriId);
        }])->get();
    
        foreach ($this->ujianList as $ujian) {
            $hasilUjian = $ujian->hasilUjians->first();

            // Mengisi totalNilaiPerUjian dengan nilai_akhir dari HasilUjian
            // Nilai akan ditampilkan jika statusnya 'selesai' atau 'menunggu_penilaian_essay'
            // Anda bisa menyesuaikan kondisi ini sesuai kebutuhan tampilan.
            $this->totalNilaiPerUjian[$ujian->id] = $hasilUjian
                ? ($hasilUjian->nilai_akhir ?? 0)
                : 0;
            
            // Opsional: Anda bisa juga menampilkan status ujian di sini
            $this->totalNilaiPerUjian[$ujian->id . '_status'] = $hasilUjian ? $hasilUjian->status : 'Belum Ujian';
        }
    }

    public function render()
    {
        return view('livewire.admin.psb.detail-ujian-santri');
    }
}