<?php

namespace App\Livewire\Admin\PSB;

use App\Livewire\SantriPPDB\UjianForm; // Mengimpor form UjianForm (mungkin digunakan sebagai sub-komponen atau form object).
use App\Models\PSB\Ujian; // Mengimpor model Ujian.
use Livewire\Attributes\Computed; // Mengimpor atribut Computed untuk properti terkomputasi.
use Livewire\Attributes\Title; // Mengimpor atribut Title untuk judul halaman.
use Livewire\Component; // Mengimpor kelas dasar Livewire Component.
use Livewire\WithPagination; // Mengimpor trait WithPagination untuk fitur paginasi.


/**
 * Kelas Livewire DashboardUjian.
 *
 * Mengelola tampilan dan logika untuk halaman dashboard ujian di sisi admin PSB.
 * Menyediakan fungsionalitas untuk melihat, mencari, memfilter, membuat, mengedit,
 * dan menghapus data ujian.
 */
class DashboardUjian extends Component
{
    use WithPagination; // Menggunakan trait untuk menambahkan fitur paginasi.
    protected $paginationTheme = 'bootstrap'; // Menetapkan tema paginasi menjadi 'bootstrap'.
    #[Title('Halaman Dashboard Ujian')] // Menetapkan judul halaman.

    public UjianForm $ujianForm; // Properti publik untuk instance UjianForm, digunakan untuk form data.

    public $ujianId; // Properti publik untuk menyimpan ID ujian saat mengedit atau menghapus.

    public $perPage = 10; // Jumlah item per halaman untuk paginasi.
    public $search = ''; // String pencarian untuk memfilter ujian berdasarkan nama.
    public $sortField = 'created_at'; // Kolom yang digunakan untuk pengurutan data.
    public $sortDirection = 'desc'; // Arah pengurutan (asc/desc).
    public $sortMataPelajaran = ''; // Filter/pengurutan berdasarkan mata pelajaran.
    public $filterTanggal = ''; // Filter berdasarkan tanggal ujian.
    public $sortStatus = ''; // Filter/pengurutan berdasarkan status ujian.

    // Properti yang akan disinkronkan dengan URL (query string).
    protected $queryString = [
        'search' => ['except' => ''], // 'search' akan ada di URL kecuali kosong.
        'sortMataPelajaran' => ['except' => ''], // 'sortMataPelajaran' akan ada di URL kecuali kosong.
        'filterTanggal' => ['except' => ''], // 'filterTanggal' akan ada di URL kecuali kosong.
        'sortStatus' => ['except' => ''], // 'sortStatus' akan ada di URL kecuali kosong.
        'perPage' => ['except' => 10], // 'perPage' akan ada di URL kecuali nilainya 10 (default).
        'sortField' => ['except' => 'created_at'], // 'sortField' akan ada di URL kecuali 'created_at' (default).
        'sortDirection' => ['except' => 'desc'] // 'sortDirection' akan ada di URL kecuali 'desc' (default).
    ];

    /**
     * Fungsi mount, dijalankan saat komponen diinisialisasi.
     * Menginisialisasi instance UjianForm.
     *
     * @return void
     */
    public function mount()
    {
        $this->ujianForm = new UjianForm($this, 'ujianForm'); // Membuat instance baru dari UjianForm.
    }

    /**
     * Mereset semua filter pencarian dan pengurutan ke nilai default.
     *
     * @return void
     */
    public function resetFilters()
    {
        $this->reset([
            'search',
            'sortMataPelajaran',
            'filterTanggal',
            'sortStatus',
            'sortField',
            'sortDirection'
        ]); // Mereset nilai properti yang ditentukan.
    }

    /**
     * Properti terkomputasi untuk mengambil daftar ujian.
     * Query akan disesuaikan berdasarkan filter pencarian dan pengurutan yang aktif.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    #[Computed] // Menandai ini sebagai properti terkomputasi (hanya dihitung ulang jika dependensi berubah).
    public function listUjian()
    {
        // Memilih kolom-kolom spesifik untuk performa yang lebih baik.
        return Ujian::select('id', 'nama_ujian', 'mata_pelajaran', 'tanggal_ujian', 'waktu_mulai', 'waktu_selesai', 'status_ujian')
            // Kondisi WHERE untuk pencarian berdasarkan nama ujian.
            ->when($this->search, function ($query) {
                $query->where('nama_ujian', 'like', '%' . $this->search . '%');
            })
            // Kondisi ORDER BY untuk pengurutan berdasarkan mata pelajaran.
            ->when($this->sortMataPelajaran, function ($query) {
                $query->orderBy('mata_pelajaran', $this->sortMataPelajaran);
            })
            // Kondisi WHERE untuk filter berdasarkan tanggal ujian.
            ->when($this->filterTanggal, function ($query) {
                $query->whereDate('tanggal_ujian', $this->filterTanggal);
            })
            // Kondisi WHERE untuk filter berdasarkan status ujian.
            ->when($this->sortStatus, function ($query) {
                $query->where('status_ujian', $this->sortStatus);
            })
            // Mengurutkan hasil berdasarkan kolom dan arah yang ditentukan.
            ->orderBy($this->sortField, $this->sortDirection)
            // Mengaplikasikan paginasi dengan jumlah item per halaman yang ditentukan.
            ->paginate($this->perPage);
    }

    /**
     * Menginisialisasi form untuk pembuatan ujian baru.
     * Mengosongkan ujianId dan mereset form.
     *
     * @return void
     */
    public function create()
    {
        $this->ujianId = null; // Menetapkan ujianId menjadi null untuk indikasi mode pembuatan.
        $this->ujianForm->reset(); // Mereset semua field pada form ujian.
    }

    /**
     * Menyimpan data ujian baru ke database.
     * Melakukan validasi input dan menampilkan pesan sukses/error.
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function createUjian()
    {
        try {
            // Melakukan validasi input dari UjianForm.
            $this->ujianForm->validate([
                'nama_ujian' => 'required|string|max:100', // Nama ujian wajib, string, maks 100 karakter.
                'mata_pelajaran' => 'required|string|max:100', // Mata pelajaran wajib, string, maks 100 karakter.
                'periode_id' => 'required|exists:psb_periodes,id', // Periode ID wajib dan harus ada di tabel psb_periodes.
                'tanggal_ujian' => 'required|date', // Tanggal ujian wajib dan format tanggal.
                'waktu_mulai' => 'required', // Waktu mulai wajib.
                'waktu_selesai' => 'required|after:waktu_mulai', // Waktu selesai wajib dan harus setelah waktu mulai.
                'status_ujian' => 'required|in:draft,aktif,selesai', // Status ujian wajib dan harus salah satu dari opsi yang ditentukan.
            ]);

            Ujian::create($this->ujianForm->all()); // Membuat record ujian baru di database menggunakan data dari form.

            session()->flash('success', 'Ujian baru berhasil dibuat!'); // Menampilkan pesan sukses.

            $this->resetPage(); // Mereset paginasi ke halaman pertama.

            return to_route('admin.master-ujian.dashboard'); // Mengalihkan ke route dashboard ujian admin.
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Menampilkan pesan error jika terjadi exception.
        }
    }

    /**
     * Memuat data ujian yang akan diedit ke dalam form.
     *
     * @param int $id ID ujian yang akan diedit.
     * @return void
     */
    public function edit($id)
    {
        $this->ujianId = $id; // Menyimpan ID ujian yang akan diedit.
        $ujianEdit = Ujian::findOrFail($id); // Mencari ujian berdasarkan ID, akan melempar 404 jika tidak ditemukan.
        $this->ujianForm->fill($ujianEdit->toArray()); // Mengisi form ujian dengan data dari objek ujian yang ditemukan.
    }

    /**
     * Memperbarui data ujian yang sudah ada di database.
     * Melakukan validasi input dan menampilkan pesan sukses/error.
     *
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function updateUjian()
    {
        try {
            // Melakukan validasi input dari UjianForm.
            $this->ujianForm->validate([
                'nama_ujian' => 'required|string|max:100', // Nama ujian wajib, string, maks 100 karakter.
                'mata_pelajaran' => 'required|string|max:100', // Mata pelajaran wajib, string, maks 100 karakter.
                'periode_id' => 'required|exists:psb_periodes,id', // Periode ID wajib dan harus ada di tabel psb_periodes.
                'tanggal_ujian' => 'required|date', // Tanggal ujian wajib dan format tanggal.
                'waktu_mulai' => 'required', // Waktu mulai wajib.
                'waktu_selesai' => 'required|after:waktu_mulai', // Waktu selesai wajib dan harus setelah waktu mulai.
                'status_ujian' => 'required|in:draft,aktif,selesai', // Status ujian wajib dan harus salah satu dari opsi yang ditentukan.
            ]);

            // Mencari ujian berdasarkan ID dan memperbarui datanya dengan data dari form.
            Ujian::findOrFail($this->ujianId)->update($this->ujianForm->all());

            session()->flash('success', 'Ujian berhasil diupdate!'); // Menampilkan pesan sukses.

            $this->resetPage(); // Mereset paginasi ke halaman pertama.

            return to_route('admin.master-ujian.dashboard'); // Mengalihkan ke route dashboard ujian admin.
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Menampilkan pesan error jika terjadi exception.
        }
    }

    /**
     * Menghapus data ujian dari database.
     *
     * @param int $id ID ujian yang akan dihapus.
     * @return void
     */
    public function deleteUjian($id)
    {
        try {
            $ujian = Ujian::findOrFail($id); // Mencari ujian berdasarkan ID.
            session()->flash('success', 'Berhasil hapus ' . $ujian->nama_ujian); // Menampilkan pesan sukses.
            $ujian->delete(); // Menghapus ujian dari database.
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage()); // Menampilkan pesan error jika terjadi exception.
        }
    }

    /**
     * Merender tampilan komponen Livewire.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.admin.psb.dashboard-ujian'); // Mengembalikan view Blade untuk komponen ini.
    }
}