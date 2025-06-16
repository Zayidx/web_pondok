<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri; // Pastikan ini di-import
use App\Models\PSB\Ujian; // Opsional, jika perlu detail ujian
use App\Models\PSB\HasilUjian; // Opsional, jika perlu detail per ujian
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class HasilUjian extends Component
{
    #[Layout('components.layouts.pendaftaran-santri-app')]
    #[Title('Hasil Ujian')]

    public $santri;
    public $totalNilaiSemuaUjian;
    public $rataRataUjian;
    public $daftarHasilUjianPerUjian; // Untuk menampilkan detail per ujian

    public function mount()
    {
        $this->santri = Auth::guard('santri')->user();

        if (!$this->santri) {
            $this->santri = PendaftaranSantri::find(session('santri_id'));
        }

        if (!$this->santri) {
            return redirect()->route('login-ppdb-santri')->with('error', 'Silakan login untuk melihat hasil ujian.');
        }

        // Mengambil total nilai dan rata-rata langsung dari model PendaftaranSantri
        $this->totalNilaiSemuaUjian = $this->santri->total_nilai_semua_ujian ?? 0;
        $this->rataRataUjian = $this->santri->rata_rata_ujian ?? 0;

        // Mengambil detail hasil per ujian
        $this->daftarHasilUjianPerUjian = HasilUjian::with('ujian')
                                            ->where('santri_id', $this->santri->id)
                                            ->orderBy('waktu_selesai', 'desc')
                                            ->get();
    }

    public function render()
    {
        return view('livewire.santri-ppdb.hasil-ujian');
    }
}