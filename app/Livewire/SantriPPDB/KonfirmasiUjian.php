<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\Ujian;
use App\Models\PSB\PendaftaranSantri;
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
        $this->santri = Auth::guard('santri')->user();
        if (!$this->santri) {
            $this->santri = PendaftaranSantri::find(session('santri_id'));
        }

        if (!$this->santri || $this->santri->status_santri !== 'sedang_ujian') {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        // Get exam data with soal count
        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);

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
        if (!$this->checklist['alat_tulis'] || !$this->checklist['koneksi'] || !$this->checklist['petunjuk'] || !$this->checklist['siap']) {
            return;
        }

        return redirect()->route('santri.mulai-ujian', ['ujianId' => $this->ujian->id]);
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.konfirmasi-ujian');
    }
} 