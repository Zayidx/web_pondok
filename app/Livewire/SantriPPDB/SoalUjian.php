<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\Ujian;
use App\Models\HasilUjian;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

class SoalUjian extends Component
{
    #[Layout('components.layouts.ujian')]
    #[Title('Soal Ujian')]

    public $ujian;
    public $santri;
    public $hasilUjian;
    public $currentSoal = 0;
    public $jawaban = [];
    public $waktuMulai;
    public $waktuSelesai;
    public $sisa_waktu;

    #[On('timeUp')]
    public function handleTimeUp()
    {
        $this->selesaiUjian();
    }

    public function mount($ujianId)
    {
        $this->santri = Auth::guard('santri')->user();
        if (!$this->santri) {
            $this->santri = PendaftaranSantri::find(session('santri_id'));
        }

        if (!$this->santri || $this->santri->status_santri !== 'sedang_ujian') {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        $this->ujian = Ujian::findOrFail($ujianId);
        
        // Check if exam is active
        if ($this->ujian->status_ujian !== 'aktif') {
            return redirect()->route('santri.dashboard-ujian')->with('error', 'Ujian tidak tersedia.');
        }

        // Check if exam is already completed
        $existingHasil = HasilUjian::where('santri_id', $this->santri->id)
            ->where('ujian_id', $this->ujian->id)
            ->where('status', 'selesai')
            ->first();

        if ($existingHasil) {
            return redirect()->route('santri.selesai-ujian', ['ujianId' => $ujianId]);
        }

        // Get or create hasil ujian
        $this->hasilUjian = HasilUjian::firstOrCreate(
            [
                'santri_id' => $this->santri->id,
                'ujian_id' => $this->ujian->id,
                'status' => 'sedang_ujian'
            ],
            [
                'waktu_mulai' => now(),
                'waktu_selesai' => null
            ]
        );

        $this->waktuMulai = $this->hasilUjian->waktu_mulai;
        $this->waktuSelesai = $this->waktuMulai->addMinutes($this->ujian->durasi);
        $this->sisa_waktu = now()->diffInSeconds($this->waktuSelesai);

        // Load saved answers if any
        $this->loadJawaban();
    }

    public function nextSoal()
    {
        if ($this->currentSoal < $this->ujian->jumlah_soal - 1) {
            $this->currentSoal++;
        }
    }

    public function prevSoal()
    {
        if ($this->currentSoal > 0) {
            $this->currentSoal--;
        }
    }

    public function saveJawaban($jawaban)
    {
        $this->jawaban[$this->currentSoal] = $jawaban;
        // Save to database
    }

    public function loadJawaban()
    {
        // Load jawaban from database
    }

    public function selesaiUjian()
    {
        // Calculate score
        $totalSkor = 0;
        $nilai = 0;
        
        // Update hasil ujian
        $this->hasilUjian->update([
            'waktu_selesai' => now(),
            'total_skor' => $totalSkor,
            'nilai' => $nilai,
            'status' => 'selesai'
        ]);

        return redirect()->route('santri.selesai-ujian', ['ujianId' => $this->ujian->id]);
    }

    public function render()
    {
        return view('livewire.santri-p-p-d-b.soal-ujian');
    }
} 