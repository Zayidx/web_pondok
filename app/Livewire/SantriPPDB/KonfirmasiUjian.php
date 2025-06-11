<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\Ujian; // Pastikan ini menggunakan namespace yang benar: App\Models\PSB\Ujian
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian; // Import model HasilUjian
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class KonfirmasiUjian extends Component
{
    #[Layout('components.layouts.ujian')]
    #[Title('Konfirmasi Ujian')]

    public $ujian;
    public $santri;
    public $durasi;
    public $jumlah_soal;
    public $checklist = [
        'alat_tulis' => false,
        'koneksi' => false,
        'petunjuk' => false,
        'siap' => false,
    ];

    public function mount($ujianId)
    {
        // Get santri data from auth
        $this->santri = Auth::guard('santri')->user() ?? PendaftaranSantri::find(session('santri_id'));

        if (!$this->santri || $this->santri->status_santri !== 'sedang_ujian') {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        // Get exam data
        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);

        // =================================================================
        // **BLOK PERBAIKAN DIMULAI DI SINI**
        // Cek apakah santri sudah pernah menyelesaikan ujian ini sebelumnya.
        $hasilSebelumnya = HasilUjian::where('santri_id', $this->santri->id)
                                     ->where('ujian_id', $this->ujian->id)
                                     ->first();

        if ($hasilSebelumnya && $hasilSebelumnya->status === 'selesai') {
            // Jika sudah selesai, kirim pesan dan redirect ke dasbor ujian.
            session()->flash('message', 'Anda sudah menyelesaikan ujian ' . $this->ujian->mata_pelajaran);
            return redirect()->route('santri.dashboard-ujian');
        }
        // **BLOK PERBAIKAN SELESAI**
        // =================================================================

        // Calculate duration
        $waktuMulai = Carbon::parse($this->ujian->waktu_mulai);
        $waktuSelesai = Carbon::parse($this->ujian->waktu_selesai);
        $this->durasi = $waktuMulai->diffInMinutes($waktuSelesai);
        
        // Get question count
        $this->jumlah_soal = $this->ujian->soals_count;

        // Check if exam is available
        if ($this->ujian->status_ujian !== 'aktif') {
            return redirect()->route('santri.dashboard-ujian')->with('error', 'Ujian belum tersedia.');
        }
    }

    public function mulaiUjian()
    {
        // Cek kembali checklist sebelum redirect
        if (in_array(false, $this->checklist, true)) {
            session()->flash('error-checklist', 'Harap centang semua checklist persiapan sebelum memulai.');
            return;
        }

        return redirect()->route('santri.mulai-ujian', ['ujianId' => $this->ujian->id]);
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.konfirmasi-ujian');
    }
}