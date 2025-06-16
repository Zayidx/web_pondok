<?php

namespace App\Livewire\Admin\PSB;
// Mendefinisikan namespace untuk kelas ini. Ini membantu mengorganisir kode sumber Anda, mencegah konflik penamaan dengan kelas lain, dan mempermudah auto-loading.
// Kelas ini berada di bawah direktori `app/Livewire/Admin/PSB`.

use Livewire\Attributes\Title;
// Mengimpor atribut `Title` dari Livewire. Atribut ini digunakan untuk mengatur judul halaman HTML secara deklaratif langsung di dalam komponen Livewire.
use Livewire\Component;
// Mengimpor kelas dasar `Component` dari Livewire. Setiap komponen Livewire harus meng-extend kelas ini untuk mendapatkan fungsionalitas Livewire.
use App\Models\PSB\PendaftaranSantri;
// Mengimpor model Eloquent `PendaftaranSantri`. Model ini merepresentasikan tabel database yang menyimpan data pendaftaran santri baru dan digunakan untuk berinteraksi dengan data tersebut.
use App\Models\PSB\Periode;
// Mengimpor model Eloquent `Periode`. Model ini merepresentasikan tabel database yang menyimpan informasi tentang periode pendaftaran (misalnya, periode aktif saat ini).
use App\Models\PSB\HasilUjian;
// Mengimpor model Eloquent `HasilUjian`. Model ini merepresentasikan tabel database yang menyimpan hasil ujian santri dan digunakan untuk mengambil data terkait ujian.
use Illuminate\Support\Facades\DB;
// Mengimpor Facade `DB` dari Laravel. Facade ini menyediakan antarmuka untuk menjalankan operasi database mentah atau menggunakan query builder Laravel secara fleksibel, terutama untuk fungsi agregat seperti `count(*)`.

class Dashboard extends Component
// Mendefinisikan kelas komponen Livewire bernama `Dashboard`. Kelas ini akan menangani logika dan data untuk tampilan dashboard pendaftaran santri baru.
{
    #[Title('Dashboard Pendaftaran Santri Baru')]
    // Menggunakan atribut `Title` untuk secara otomatis mengatur judul tab atau jendela browser menjadi "Dashboard Pendaftaran Santri Baru" ketika komponen ini dirender.
    public function render()
    // Metode `render()` adalah metode utama dan wajib dalam setiap komponen Livewire.
    // Metode ini bertanggung jawab untuk mengambil data yang dibutuhkan dan mengembalikan view Blade yang akan dirender ke browser.
    {
        // Get active period
        // Mendapatkan periode pendaftaran yang sedang aktif.
        $periode = Periode::where('status_periode', 'active')->first();
        // Mengambil satu catatan dari tabel `Periode` di mana kolom `status_periode` bernilai 'active'.
        // `first()` mengambil baris pertama yang cocok atau `null` jika tidak ada yang ditemukan.

        // Get registration statistics
        // Bagian ini mengumpulkan berbagai statistik terkait pendaftaran santri.

        $totalPendaftar = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Memulai query pada model `PendaftaranSantri`.
            // `when($periode, function($query) use ($periode) { ... })` adalah helper Eloquent. Callback (fungsi anonim) di dalamnya hanya akan dieksekusi jika variabel `$periode` tidak null (yaitu, ada periode aktif yang ditemukan).
            return $query->where('periode_id', $periode->id);
            // Jika ada periode aktif, tambahkan kondisi ke query untuk memfilter pendaftar berdasarkan `periode_id` yang sesuai dengan ID periode aktif.
        })->count();
        // Menghitung total jumlah pendaftar setelah menerapkan filter periode (jika ada).

        $pendaftarByGender = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Memulai query baru untuk pendaftar berdasarkan jenis kelamin, dengan filter periode yang sama.
            return $query->where('periode_id', $periode->id);
        })
        ->select('jenis_kelamin', DB::raw('count(*) as total'))
        // Memilih kolom `jenis_kelamin` dan menghitung jumlah total (`count(*)`) untuk setiap kelompok, menyatakannya sebagai alias `total`.
        // `DB::raw()` digunakan untuk menulis ekspresi SQL mentah.
        ->groupBy('jenis_kelamin')
        // Mengelompokkan hasil berdasarkan `jenis_kelamin` sehingga `count(*)` dihitung per jenis kelamin.
        ->get();
        // Mengeksekusi query dan mendapatkan hasilnya sebagai koleksi.

        $pendaftarByProgram = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Memulai query baru untuk pendaftar berdasarkan tipe pendaftaran/program, dengan filter periode yang sama.
            return $query->where('periode_id', $periode->id);
        })
        ->select('tipe_pendaftaran', DB::raw('count(*) as total'))
        // Memilih kolom `tipe_pendaftaran` dan menghitung jumlah total untuk setiap kelompok.
        ->groupBy('tipe_pendaftaran')
        // Mengelompokkan hasil berdasarkan `tipe_pendaftaran`.
        ->get();
        // Mengeksekusi query.

        $pendaftarByStatus = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Memulai query baru untuk pendaftar berdasarkan status santri, dengan filter periode yang sama.
            return $query->where('periode_id', $periode->id);
        })
        ->select('status_santri', DB::raw('count(*) as total'))
        // Memilih kolom `status_santri` dan menghitung jumlah total untuk setiap kelompok.
        ->groupBy('status_santri')
        // Mengelompokkan hasil berdasarkan `status_santri`.
        ->get();
        // Mengeksekusi query.

        // Get total santri yang sedang wawancara
        // Menghitung jumlah santri dengan status 'wawancara'.
        $totalWawancara = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Menerapkan filter periode jika ada.
            return $query->where('periode_id', $periode->id);
        })
        ->where('status_santri', 'wawancara')
        // Menambahkan kondisi untuk hanya menghitung santri yang `status_santri`-nya adalah 'wawancara'.
        ->count();
        // Menghitung total jumlah.

        // Get total santri yang sedang ujian
        // Menghitung jumlah santri dengan status 'sedang_ujian'.
        $totalUjian = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Menerapkan filter periode.
            return $query->where('periode_id', $periode->id);
        })
        ->where('status_santri', 'sedang_ujian')
        // Menambahkan kondisi `status_santri` adalah 'sedang_ujian'.
        ->count();
        // Menghitung total jumlah.

        // Get total santri yang diterima
        // Menghitung jumlah santri dengan status 'diterima'.
        $totalDiterima = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Menerapkan filter periode.
            return $query->where('periode_id', $periode->id);
        })
        ->where('status_santri', 'diterima')
        // Menambahkan kondisi `status_santri` adalah 'diterima'.
        ->count();
        // Menghitung total jumlah.

        // Get total santri yang ditolak
        // Menghitung jumlah santri dengan status 'ditolak'.
        $totalDitolak = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Menerapkan filter periode.
            return $query->where('periode_id', $periode->id);
        })
        ->where('status_santri', 'ditolak')
        // Menambahkan kondisi `status_santri` adalah 'ditolak'.
        ->count();
        // Menghitung total jumlah.

        // Get recent registrations
        // Mendapatkan daftar pendaftaran santri terbaru.
        $recentRegistrations = PendaftaranSantri::when($periode, function($query) use ($periode) {
            // Menerapkan filter periode.
            return $query->where('periode_id', $periode->id);
        })
        ->with(['periode', 'hasilUjians'])
        // Menggunakan eager loading untuk memuat relasi `periode` dan `hasilUjians` dari setiap pendaftaran.
        // Ini mencegah masalah N+1 query, membuat fetching data lebih efisien.
        ->latest()
        // Mengurutkan hasil berdasarkan kolom `created_at` secara descending (terbaru).
        ->take(5)
        // Membatasi hasil hanya pada 5 pendaftaran terbaru.
        ->get();
        // Mengeksekusi query dan mendapatkan hasilnya.

        // Get exam statistics
        // Mengumpulkan statistik terkait hasil ujian.
        $examStats = HasilUjian::when($periode, function($query) use ($periode) {
            // Memulai query pada model `HasilUjian`.
            // Menerapkan filter periode pada relasi `santri` yang terkait dengan `HasilUjian`.
            return $query->whereHas('santri', function($q) use ($periode) {
                // `whereHas` memastikan bahwa hanya `HasilUjian` yang terkait dengan `santri` dari periode aktif yang akan dipertimbangkan.
                $q->where('periode_id', $periode->id);
            });
        })
        ->select('status', DB::raw('count(*) as total'))
        // Memilih kolom `status` (status hasil ujian) dan menghitung total untuk setiap kelompok.
        ->groupBy('status')
        // Mengelompokkan hasil berdasarkan `status` ujian.
        ->get();
        // Mengeksekusi query.

        return view('livewire.admin.psb.dashboard', [
            // Mengembalikan view Blade yang terletak di `resources/views/livewire/admin/psb/dashboard.blade.php`.
            // Data yang dikembalikan dalam array ini akan tersedia sebagai variabel di view.
            'periode' => $periode,
            // Mengirim objek `$periode` (periode aktif) ke view.
            'totalPendaftar' => $totalPendaftar,
            // Mengirim total jumlah pendaftar.
            'pendaftarByGender' => $pendaftarByGender,
            // Mengirim statistik pendaftar berdasarkan jenis kelamin.
            'pendaftarByProgram' => $pendaftarByProgram,
            // Mengirim statistik pendaftar berdasarkan program.
            'pendaftarByStatus' => $pendaftarByStatus,
            // Mengirim statistik pendaftar berdasarkan status santri.
            'recentRegistrations' => $recentRegistrations,
            // Mengirim daftar pendaftaran terbaru.
            'totalWawancara' => $totalWawancara,
            // Mengirim total santri yang sedang wawancara.
            'totalUjian' => $totalUjian,
            // Mengirim total santri yang sedang ujian.
            'totalDiterima' => $totalDiterima,
            // Mengirim total santri yang diterima.
            'totalDitolak' => $totalDitolak,
            // Mengirim total santri yang ditolak.
            'examStats' => $examStats,
            // Mengirim statistik hasil ujian.
        ]);
    }
}