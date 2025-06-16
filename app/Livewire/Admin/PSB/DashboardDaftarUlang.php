<?php

namespace App\Livewire\Admin\PSB;
// Mendefinisikan namespace untuk kelas ini. Ini membantu mengorganisir kode dan menghindari konflik nama kelas.
// Kelas ini berada di bawah direktori `app/Livewire/Admin/PSB`.

use Livewire\Component;
// Mengimpor kelas dasar `Component` dari Livewire. Setiap komponen Livewire harus meng-extend kelas ini.
use App\Models\PSB\PendaftaranSantri;
// Mengimpor model Eloquent `PendaftaranSantri`. Model ini digunakan untuk berinteraksi dengan tabel database yang menyimpan data pendaftaran santri.
use Illuminate\Support\Facades\Storage;
// Mengimpor Facade `Storage` dari Laravel. Digunakan untuk berinteraksi dengan sistem file, seperti menyimpan atau mengambil file (contohnya bukti pembayaran).
use Livewire\WithPagination;
// Mengimpor trait `WithPagination` dari Livewire. Trait ini menambahkan fungsionalitas paginasi (pembagian halaman) ke komponen Livewire.
use Livewire\Attributes\Title;
// Mengimpor atribut `Title` dari Livewire. Ini adalah fitur baru di Livewire 3 untuk mengatur judul halaman secara deklaratif.
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

// Mengimpor atribut `Computed` dari Livewire. Ini digunakan untuk mendefinisikan properti yang nilainya dihitung dan di-cache, hanya dihitung ulang jika dependensi berubah.

#[Title('Dashboard Daftar Ulang')]
// Menggunakan atribut `Title` untuk secara otomatis mengatur judul halaman HTML menjadi "Dashboard Daftar Ulang" ketika komponen ini dirender.
class DashboardDaftarUlang extends Component
// Mendefinisikan kelas komponen Livewire `DashboardDaftarUlang` yang meng-extend `Livewire\Component`.
{
    use WithPagination; // Enables pagination functionality for the component
    // Menggunakan trait `WithPagination`. Ini menyediakan metode-metode seperti `paginate` dan `resetPage` untuk manajemen paginasi.
    #[Layout ('components.layouts.app')]

    // Public properties for filters, search, and pagination settings
    // Properti publik di Livewire secara otomatis tersedia di view dan dapat diikat (bind) ke elemen input HTML.
    public int $perPage = 10; // Number of items to display per page
    // Mendefinisikan properti `$perPage` dengan tipe `int` dan nilai default 10. Ini mengontrol berapa banyak item yang ditampilkan per halaman.
    public string $search = ''; // Search term for filtering registrations
    // Mendefinisikan properti `$search` dengan tipe `string` dan nilai default string kosong. Ini akan menyimpan kata kunci pencarian dari input pengguna.
    public array $filters = ['status' => '', 'tipe' => '', 'urutan' => 'terbaru']; // Array for various filters (status, type, order)
    // Mendefinisikan properti `$filters` sebagai array. Ini menyimpan nilai-nilai untuk berbagai filter yang digunakan di tabel (status pembayaran, tipe pendaftaran, urutan data).
    public string $sortField = 'created_at'; // Default field for sorting
    // Mendefinisikan properti `$sortField`. Ini menyimpan nama kolom database yang saat ini digunakan untuk sorting. Default-nya adalah `created_at`.
    public string $sortDirection = 'desc'; // Default sort direction
    // Mendefinisikan properti `$sortDirection`. Ini menyimpan arah sorting ('asc' untuk ascending, 'desc' untuk descending). Default-nya adalah 'desc'.

    // Public properties for modal states and selected data
    // Properti-properti ini mengontrol status modal (pop-up) dan data yang ditampilkan di dalamnya.
   // Properti untuk mengontrol modal
    public bool $showDetailModal = false;
    public bool $showProofModal = false;
    public ?PendaftaranSantri $selectedRegistration = null;
    public ?string $proofImageUrl = null;
    public bool $showEditModal = false; // Mengontrol visibilitas modal edit
    public ?int $editingId = null; // Menyimpan ID santri yang sedang diedit
    protected string $paginationTheme = 'bootstrap'; // Specifies the Bootstrap pagination theme
    // Mengatur tema paginasi menjadi 'bootstrap'. Ini memastikan tampilan paginasi sesuai dengan gaya Bootstrap.
    public array $editForm = [
        'nama_lengkap' => '',
        'nisn' => '',
        'asal_sekolah' => '',
        'status_santri' => '',
    ];
    protected function rules()
    {
        return [
            'editForm.nama_lengkap' => 'required|string|max:255',
            'editForm.nisn' => 'required|string|max:20|unique:psb_pendaftaran_santri,nisn,' . $this->editingId,
            'editForm.asal_sekolah' => 'required|string|max:255',
            'editForm.status_santri' => 'required|in:menunggu,wawancara,sedang_ujian,diterima,ditolak,daftar_ulang',
        ];
    }    
    /**
     * Resets pagination to the first page when any of the specified properties change.
     * This ensures that filters and search terms are applied correctly.
     *
     * @param string $key The name of the property being updated.
     */
    public function updating(string $key): void
    // Ini adalah lifecycle hook Livewire yang otomatis dipanggil sebelum properti publik diupdate.
    // `$key` adalah nama properti yang akan diupdate (misalnya, 'search', 'filters.status').
    {
        // Check if the updated key is related to search, perPage, or any filter
        // Memeriksa apakah properti yang sedang diupdate adalah 'search', 'perPage', atau bagian dari array 'filters'.
        // `explode('.', $key)[0]` akan mengambil bagian pertama dari nama properti jika itu adalah array (misalnya, 'filters' dari 'filters.tipe').
        if (in_array(explode('.', $key)[0], ['search', 'perPage', 'filters'])) {
            $this->resetPage(); // Reset to the first page
            // Memanggil metode `resetPage()` dari trait `WithPagination`. Ini mengatur ulang paginasi kembali ke halaman pertama.
            // Ini penting agar hasil pencarian atau filter yang baru selalu dimulai dari halaman pertama.
        }
    }

    /**
     * Sorts the table by the given field. Toggles sort direction if the same field is clicked again.
     *
     * @param string $field The database column to sort by.
     */
    public function sortBy(string $field): void
    // Metode ini dipanggil ketika header kolom tabel diklik untuk melakukan sorting.
    // `$field` adalah nama kolom database yang akan disortir.
    {
        // If clicking the currently sorted field, toggle direction
        // Memeriksa apakah kolom yang diklik saat ini adalah kolom yang sama dengan `$sortField` yang sudah aktif.
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            // Jika kolomnya sama, arah sorting di-toggle (dari 'asc' menjadi 'desc', atau sebaliknya).
        } else {
            // Otherwise, set new field and default to ascending
            // Jika kolom yang diklik berbeda, set `$sortField` ke kolom baru dan set `$sortDirection` ke 'asc' (default ascending untuk kolom baru).
            $this->sortDirection = 'asc';
            $this->sortField = $field;
        }
        // Ensure manual 'urutan' filter doesn't conflict with direct column sorting
        // Mengatur ulang filter 'urutan' menjadi kosong. Ini penting agar sorting manual melalui header kolom tidak berkonflik dengan filter 'urutan' yang mungkin ada.
        $this->filters['urutan'] = '';
    }

    /**
     * Resets all search and filter parameters to their default values.
     * Also resets the pagination to the first page.
     */
    public function resetFilters(): void
    // Metode ini dipanggil ketika tombol "Reset Filter" diklik.
    {
        $this->reset(['search', 'filters']); // Reset search term and filters array
        // Menggunakan metode `reset()` bawaan Livewire untuk mengatur ulang properti `$search` dan `$filters` ke nilai default awalnya.
        $this->resetPage(); // Reset pagination
        // Mengatur ulang paginasi kembali ke halaman pertama.
    }

    /**
     * Displays the detail modal for a specific registration.
     *
     * @param int $id The ID of the PendaftaranSantri to display.
     */
    public function showDetail(int $id): void
{
    // 1. Cari data pendaftaran santri
    $registration = PendaftaranSantri::find($id);

    // 2. BUAT PERCABANGAN: Periksa apakah data ditemukan
    if ($registration) {
        // JIKA DITEMUKAN: Lanjutkan seperti biasa
        $this->selectedRegistration = $registration;
        $this->showDetailModal = true;
    } else {
        // JIKA TIDAK DITEMUKAN:
        // Jangan buka modal dan beri pesan error kepada admin.
        session()->flash('error', 'Data pendaftaran dengan ID ' . $id . ' tidak dapat ditemukan.');
    }
}



    /**
     * Closes the detail modal and clears the selected registration data.
     */
    public function closeModal(): void
    // Metode ini dipanggil ketika modal detail ditutup.
    {
        $this->showDetailModal = false; // Close the detail modal
        // Mengatur `$showDetailModal` menjadi `false` untuk menyembunyikan modal.
        $this->selectedRegistration = null; // Clear selected registration to prevent data leakage
        // Mengatur `$selectedRegistration` menjadi `null`. Ini penting untuk membersihkan data sensitif atau besar setelah modal ditutup.
    }

    /**
     * Displays the payment proof image modal for a specific registration.
     *
     * @param int $id The ID of the PendaftaranSantri whose payment proof is to be viewed.
     */
    public function viewPaymentProof(int $id): void
    {
        // 1. Cari data santri
        $registration = PendaftaranSantri::findOrFail($id);
        
        // 2. AMBIL BUKTI PEMBAYARAN DARI RELASI
        $pembayaran = $registration->pembayaranTerbaru;
    
        // 3. Periksa apakah pembayaran dan path buktinya ada
        if ($pembayaran && $pembayaran->bukti_pembayaran) {
            // Generate a public URL for the stored image
            $this->proofImageUrl = Storage::url($pembayaran->bukti_pembayaran);
            $this->showProofModal = true;
        } else {
            session()->flash('error', 'Bukti pembayaran untuk santri ini tidak ditemukan.');
        }
    }

    /**
     * Closes the payment proof modal and clears the image URL.
     */
    public function closeProofModal(): void
    // Metode ini dipanggil ketika modal bukti pembayaran ditutup.
    {
        $this->showProofModal = false; // Close the payment proof modal
        // Mengatur `$showProofModal` menjadi `false` untuk menyembunyikan modal.
        $this->proofImageUrl = null; // Clear the image URL
        // Mengatur `$proofImageUrl` menjadi `null` untuk membersihkan URL gambar setelah modal ditutup.
    }

    /**
     * Verifies a registration, updating its status to 'verified' and 'diterima'.
     *
     * @param int $id The ID of the PendaftaranSantri to verify.
     */
    public function verifyRegistration(int $id): void
{
    // 1. Cari data pendaftaran santri
    $registration = PendaftaranSantri::findOrFail($id);

    // 2. TEMUKAN DATA PEMBAYARAN TERBARU YANG TERKAIT
    $pembayaran = $registration->pembayaranTerbaru;

    // 3. Jika ada data pembayaran, update statusnya
    if ($pembayaran) {
        $pembayaran->update([
            'status_pembayaran' => 'verified', // Update status di tabel psb_pembayaran
        ]);
    }

    // 4. Update status santri di tabel pendaftaran_santri
    $registration->update([
        'status_santri' => 'diterima', // Atau status lain yang sesuai
        'verified_at' => now(),
        'verified_by' => auth()->id(),
    ]);

    session()->flash('success', 'Pendaftaran ulang ' . $registration->nama_lengkap . ' telah diterima.');
    $this->closeModal();
}
    /**
     * Rejects a registration's payment proof, setting status to 'rejected' and clearing the proof.
     *
     * @param int $id The ID of the PendaftaranSantri whose payment proof is to be rejected.
     */
    public function rejectRegistration(int $id): void
    {
        // 1. Cari data pendaftaran santri
        $registration = PendaftaranSantri::findOrFail($id);
        
        // 2. TEMUKAN DATA PEMBAYARAN TERBARU YANG TERKAIT
        $pembayaran = $registration->pembayaranTerbaru;
    
        // 3. Jika ada data pembayaran, update statusnya menjadi 'rejected'
        if ($pembayaran) {
            $pembayaran->update([
                'status_pembayaran' => 'rejected',
            ]);
    
            // Catatan: Anda mungkin tidak ingin menghapus bukti pembayaran, 
            // agar admin bisa melihat bukti yang ditolak.
            // Jika tetap ingin menghapus, baris di bawah ini bisa diaktifkan:
            // Storage::delete($pembayaran->bukti_pembayaran);
            // $pembayaran->update(['bukti_pembayaran' => null]);
        }
    
        session()->flash('success', 'Bukti pembayaran ' . $registration->nama_lengkap . ' ditolak.');
        $this->closeModal();
    }
    
// File: app/Livewire/Admin/PSB/DashboardDaftarUlang.php

// ... (setelah method rejectRegistration)

/**
 * Mempersiapkan dan membuka modal untuk mengedit data pendaftaran.
 *
 * @param int $id ID dari PendaftaranSantri yang akan diedit.
 */
public function edit(int $id): void
{
    // Cari pendaftaran berdasarkan ID, jika tidak ketemu akan gagal.
    $registration = PendaftaranSantri::findOrFail($id);

    // Simpan ID yang sedang diedit
    $this->editingId = $registration->id;

    // Isi properti $editForm dengan data yang ada
    $this->editForm['nama_lengkap'] = $registration->nama_lengkap;
    $this->editForm['nisn'] = $registration->nisn;
    $this->editForm['asal_sekolah'] = $registration->asal_sekolah;
    $this->editForm['status_santri'] = $registration->status_santri;

    // Tampilkan modal edit
    $this->showEditModal = true;
}

/**
 * Mengupdate data pendaftaran yang telah diedit.
 */
public function update(): void
{
    // Lakukan validasi data dari form edit
    $validatedData = $this->validate();

    // Cari pendaftaran yang akan diupdate
    $registration = PendaftaranSantri::find($this->editingId);

    if ($registration) {
        // Update data pendaftaran dengan data dari form
        $registration->update($validatedData['editForm']);

        // Kirim notifikasi sukses
        session()->flash('success', 'Data ' . $validatedData['editForm']['nama_lengkap'] . ' berhasil diperbarui.');

        // Tutup modal edit
        $this->closeEditModal();
    }
}

/**
 * Menutup modal edit dan mereset properti terkait.
 */
public function closeEditModal(): void
{
    $this->showEditModal = false;
    $this->reset('editForm', 'editingId');
}
    /**
     * Computed property that returns options for 'tipe pendaftaran' (registration type) filter.
     * This is cached and only re-evaluated if dependencies change.
     *
     * @return array
     */
    #[Computed]
    // Atribut `Computed` menandakan bahwa properti ini adalah properti yang dihitung.
    // Livewire akan meng-cache hasilnya dan hanya menghitung ulang jika ada properti yang diakses di dalamnya berubah.
    public function tipeOptions(): array
    // Metode ini mengembalikan array opsi untuk filter 'tipe pendaftaran'.
    {
        return [
            '' => 'Semua Tipe', // Added 'All Types' option
            // Opsi default untuk menampilkan semua tipe. Kunci kosong ('') sering digunakan untuk opsi "Semua".
            'reguler' => 'Reguler',
            'olimpiade' => 'Olimpiade',
            'internasional' => 'Internasional',
        ];
    }

    /**
     * Computed property that returns options for 'status pembayaran' (payment status) filter.
     *
     * @return array
     */
    #[Computed]
    // Properti terhitung lainnya untuk opsi filter 'status pembayaran'.
    public function statusPaymentOptions(): array
    // Metode ini mengembalikan array opsi untuk filter 'status pembayaran'.
    {
        return [
            '' => 'Semua Status', // Added 'All Status' option
            // Opsi default untuk menampilkan semua status.
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            'no_proof' => 'Menunggu Bukti', // Represents registrations without proof
            // Menambahkan status khusus 'no_proof' untuk pendaftaran yang belum mengunggah bukti pembayaran. Ini sangat membantu untuk filter UI.
        ];
    }

    /**
     * The main rendering method for the Livewire component.
     * This method constructs the query for registrations based on current filters and pagination.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        // Memulai query dan eager load relasi pembayaranTerbaru untuk performa
        $query = PendaftaranSantri::with('pembayaranTerbaru');

        // Filter status santri seperti sebelumnya
        $query->whereIn('status_santri', ['diterima', 'daftar_ulang']);

        // Filter pencarian seperti sebelumnya
        $query->when($this->search, function ($q) {
            $q->where(function ($subQuery) {
                $subQuery->where('nama_lengkap', 'like', '%' . $this->search . '%')
                         ->orWhere('nisn', 'like', '%' . $this->search . '%');
            });
        });
        
        // Filter tipe pendaftaran seperti sebelumnya
        $query->when($this->filters['tipe'], function ($q) {
            $q->where('tipe_pendaftaran', $this->filters['tipe']);
        });

       // INTI LOGIKA FILTER STATUS:
    $query->when($this->filters['status'], function ($q) {
        $status = $this->filters['status'];

        if ($status === 'no_proof') {
            // Cari santri yang TIDAK PUNYA data di tabel `psb_pembayaran` sama sekali.
            $q->whereDoesntHave('pembayaranHistory');
        } else {
            // Cari santri yang PUNYA data pembayaran dengan status tertentu.
            // `whereHas` akan "melirik" ke tabel relasi.
            $q->whereHas('pembayaranTerbaru', function ($subQuery) use ($status) {
                // Kondisi filter diterapkan pada tabel `psb_pembayaran`
                $subQuery->where('status_pembayaran', $status);
            });
        }
    });

        // Logika sorting tetap sama
        if ($this->filters['urutan'] === 'terbaru') {
            $query->orderBy('created_at', 'desc');
        } elseif ($this->filters['urutan'] === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return view('livewire.admin.psb.dashboard-daftar-ulang', [
            'registrations' => $query->paginate($this->perPage),
        ]);
    }
}