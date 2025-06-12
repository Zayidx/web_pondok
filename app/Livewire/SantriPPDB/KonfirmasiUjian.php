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

/**
 * Kelas Livewire KonfirmasiUjian.
 *
 * Mengelola tampilan dan logika untuk halaman konfirmasi ujian bagi santri.
 * Memastikan santri memenuhi prasyarat sebelum memulai ujian dan mengelola
 * status ujian serta pengalihan halaman.
 */
class KonfirmasiUjian extends Component
{
    #[Layout('components.layouts.ujian')] // Menetapkan layout Blade yang akan digunakan untuk komponen ini.
    #[Title('Konfirmasi Ujian')] // Menetapkan judul halaman untuk komponen ini.

    public $ujian; // Properti publik untuk menyimpan data objek ujian.
    public $santri; // Properti publik untuk menyimpan data objek santri yang sedang login.
    public $durasi; // Properti publik untuk menyimpan durasi ujian dalam menit.
    public $jumlah_soal; // Properti publik untuk menyimpan jumlah total soal dalam ujian.
    public $checklist = [ // Properti publik untuk menyimpan status checklist persiapan ujian.
        'alat_tulis' => false, // Status checklist untuk 'alat tulis'.
        'koneksi' => false,    // Status checklist untuk 'koneksi internet'.
        'petunjuk' => false,   // Status checklist untuk 'membaca petunjuk'.
        'siap' => false,       // Status checklist untuk 'kesiapan memulai'.
    ];

    /**
     * Fungsi mount, dijalankan saat komponen diinisialisasi.
     * Mengambil data santri dan ujian, serta melakukan validasi.
     *
     * @param int $ujianId ID ujian yang akan dikonfirmasi.
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function mount($ujianId)
    {
        // Mendapatkan data santri dari guard 'santri' atau dari session jika ada.
        $this->santri = Auth::guard('santri')->user() ?? PendaftaranSantri::find(session('santri_id'));

        // Memeriksa apakah santri tidak ditemukan atau statusnya tidak 'sedang_ujian'.
        // Jika tidak, logout santri dan arahkan kembali ke halaman login dengan pesan error.
        if (!$this->santri || $this->santri->status_santri !== 'sedang_ujian') {
            Auth::guard('santri')->logout();
            return redirect()->route('login-ppdb-santri')->with('error', 'Anda tidak memiliki akses ke halaman ujian.');
        }

        // Mendapatkan data ujian beserta jumlah soal yang terkait. Jika tidak ditemukan, akan melempar 404.
        $this->ujian = Ujian::withCount('soals')->findOrFail($ujianId);

        // =================================================================
        // **BLOK PERBAIKAN DIMULAI DI SINI**
        // Cek apakah santri sudah pernah menyelesaikan ujian ini sebelumnya.
        $hasilSebelumnya = HasilUjian::where('santri_id', $this->santri->id) // Mencari hasil ujian berdasarkan ID santri.
                                     ->where('ujian_id', $this->ujian->id) // Mencari hasil ujian berdasarkan ID ujian.
                                     ->first(); // Mengambil satu hasil ujian pertama yang cocok.

        // Jika ditemukan hasil ujian sebelumnya dan statusnya 'selesai'.
        if ($hasilSebelumnya && $hasilSebelumnya->status === 'selesai') {
            // Jika sudah selesai, kirim pesan flash dan redirect ke dasbor ujian.
            session()->flash('message', 'Anda sudah menyelesaikan ujian ' . $this->ujian->mata_pelajaran); // Mengatur pesan flash.
            return redirect()->route('santri.mulai-ujian'); // Mengalihkan ke dashboard ujian.
        }
        // **BLOK PERBAIKAN SELESAI**
        // =================================================================

        // Menghitung durasi ujian berdasarkan waktu mulai dan waktu selesai.
        // Nilai waktu_mulai diambil langsung dari kolom 'waktu_mulai' pada tabel 'ujians'.
        $waktuMulai = Carbon::parse($this->ujian->waktu_mulai); 
        // Nilai waktu_selesai diambil langsung dari kolom 'waktu_selesai' pada tabel 'ujians'.
        $waktuSelesai = Carbon::parse($this->ujian->waktu_selesai); 
        $this->durasi = $waktuMulai->diffInMinutes($waktuSelesai); // Menghitung selisih waktu dalam menit.
        
        // Mengambil jumlah soal dari properti `soals_count` yang ditambahkan oleh `withCount`.
        $this->jumlah_soal = $this->ujian->soals_count;

        // Memeriksa apakah status ujian tidak 'aktif'.
        // Jika tidak, arahkan kembali ke dasbor ujian dengan pesan error.
        if ($this->ujian->status_ujian !== 'aktif') {
            return redirect()->route('santri.mulai-ujian')->with('error', 'Ujian belum tersedia.');
        }
    }

    /**
     * Fungsi untuk memulai ujian.
     * Akan dijalankan saat tombol 'Mulai Ujian' diklik.
     * Melakukan validasi checklist sebelum mengarahkan ke halaman ujian.
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function mulaiUjian()
    {
        // Memeriksa apakah ada item checklist yang bernilai false.
        if (in_array(false, $this->checklist, true)) {
            session()->flash('error-checklist', 'Harap centang semua checklist persiapan sebelum memulai.'); // Mengatur pesan error flash.
            return; // Menghentikan eksekusi fungsi.
        }

        // Mengarahkan santri ke halaman ujian dengan ID ujian.
        return redirect()->route('santri.mulai-ujian', ['ujianId' => $this->ujian->id]);
    }

    /**
     * Fungsi render, merender tampilan komponen Livewire.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.santri-p-p-d-b.konfirmasi-ujian'); // Mengembalikan view Blade untuk komponen ini.
    }
}