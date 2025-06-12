<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\PSB\Ujian;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\HasilUjian;
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

    public $examEndTime; // Properti baru untuk menyimpan waktu berakhir ujian
    public $examHasEnded = false; // Properti baru untuk menandai jika ujian sudah berakhir

    public function mount($ujianId)
    {
        // Pastikan santri terautentikasi dan memiliki status yang benar
    $this->santri = Auth::guard('santri')->user() ?? PendaftaranSantri::find(session('santri_id'));
    if (!$this->santri || $this->santri->status_santri !== 'sedang_ujian') {
        Auth::guard('santri')->logout();
        return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
    }

    // Muat ujian
    $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);

    // Gabungkan tanggal ujian dan waktu selesai untuk mendapatkan datetime penuh
    $this->examEndTime = Carbon::parse($this->ujian->tanggal_ujian->format('Y-m-d') . ' ' . $this->ujian->waktu_selesai);

    // Debugging waktu
    // dd([
    //     'Current Time' => Carbon::now()->toDateTimeString(),
    //     'Exam End Time' => $this->examEndTime->toDateTimeString(),
    //     'Ujian Waktu Selesai DB' => $this->ujian->waktu_selesai,
    //     'Ujian Tanggal Ujian DB' => $this->ujian->tanggal_ujian->format('Y-m-d'),
    //     'Exam Has Ended (before check)' => $this->examHasEnded,
    //     'Is Current Time >= Exam End Time' => Carbon::now()->greaterThanOrEqualTo($this->examEndTime),
    // ]);

    // Periksa apakah waktu saat ini sudah melewati waktu selesai ujian
    if (Carbon::now()->greaterThanOrEqualTo($this->examEndTime)) {
        $this->examHasEnded = true;
        session()->flash('error', 'Waktu untuk mengerjakan ujian "' . $this->ujian->nama_ujian . '" sudah berakhir.');
        return redirect()->route('santri.dashboard-ujian');
    }
        // BLOK PEMERIKSAAN WAKTU SELESAI DI SINI
        // =================================================================

        // Cek apakah santri sudah pernah menyelesaikan ujian ini sebelumnya.
        $hasilSebelumnya = HasilUjian::where('santri_id', $this->santri->id)
                                     ->where('ujian_id', $this->ujian->id)
                                     ->first();

        if ($hasilSebelumnya && $hasilSebelumnya->status === 'selesai') {
            // Jika sudah selesai, kirim pesan dan redirect ke dasbor ujian.
            session()->flash('message', 'Anda sudah menyelesaikan ujian ' . $this->ujian->mata_pelajaran);
            return redirect()->route('santri.dashboard-ujian');
        }

        // Calculate duration
        $waktuMulai = Carbon::parse($this->ujian->waktu_mulai);
        // Menggunakan waktu selesai ujian yang sebenarnya dari model Ujian
        $this->durasi = $waktuMulai->diffInMinutes($this->examEndTime);
        
        // Get question count
        $this->jumlah_soal = $this->ujian->soals_count;

        // Check if exam is available
        if ($this->ujian->status_ujian !== 'aktif') {
            session()->flash('error', 'Ujian belum tersedia.');
            return redirect()->route('santri.dashboard-ujian');
        }
    }

    public function mulaiUjian()
    {
        // Periksa kembali jika ujian sudah berakhir (untuk menghindari double submit atau celah waktu)
        if ($this->examHasEnded || Carbon::now()->greaterThanOrEqualTo($this->examEndTime)) {
            session()->flash('error', 'Waktu untuk mengerjakan ujian "' . $this->ujian->nama_ujian . '" sudah berakhir. Anda tidak bisa memulai ujian ini.');
            return redirect()->route('santri.dashboard-ujian');
        }

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
