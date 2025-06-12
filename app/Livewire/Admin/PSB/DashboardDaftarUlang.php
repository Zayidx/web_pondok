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
    public bool $showDetailModal = false; // Controls visibility of the detail modal
    // Mendefinisikan `$showDetailModal` sebagai boolean. Jika `true`, modal detail akan ditampilkan di view.
    public bool $showProofModal = false; // Controls visibility of the payment proof modal
    // Mendefinisikan `$showProofModal` sebagai boolean. Jika `true`, modal bukti pembayaran akan ditampilkan di view.
    public ?PendaftaranSantri $selectedRegistration = null; // Holds the selected registration model for details
    // Mendefinisikan `$selectedRegistration` sebagai properti yang dapat menampung instance model `PendaftaranSantri` atau `null`. Ini menyimpan data pendaftaran yang sedang dilihat di modal detail.
    public ?string $proofImageUrl = null; // Stores the URL of the payment proof image
    // Mendefinisikan `$proofImageUrl` sebagai string yang dapat bernilai `null`. Ini menyimpan URL gambar bukti pembayaran yang akan ditampilkan di modal bukti pembayaran.

    protected string $paginationTheme = 'bootstrap'; // Specifies the Bootstrap pagination theme
    // Mengatur tema paginasi menjadi 'bootstrap'. Ini memastikan tampilan paginasi sesuai dengan gaya Bootstrap.

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
    // Metode ini dipanggil ketika tombol "Detail Pembayaran" diklik.
    // `$id` adalah ID dari data pendaftaran santri yang ingin ditampilkan detailnya.
    {
        // Find the registration by ID; if not found, $selectedRegistration will be null
        $this->selectedRegistration = PendaftaranSantri::find($id);
        // Mencari data pendaftaran santri berdasarkan `$id` menggunakan Eloquent.
        // Hasilnya (model atau `null` jika tidak ditemukan) disimpan di properti `$selectedRegistration`.
        $this->showDetailModal = true; // Open the detail modal
        // Mengatur properti `$showDetailModal` menjadi `true` untuk menampilkan modal detail di view.
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
    // Metode ini dipanggil ketika tombol "Lihat Bukti" diklik.
    // `$id` adalah ID dari data pendaftaran yang bukti pembayarannya ingin dilihat.
    {
        $registration = PendaftaranSantri::find($id); // Find the registration by ID
        // Mencari data pendaftaran santri berdasarkan `$id`.
        // Check if registration exists and has a payment proof
        // Memeriksa apakah data pendaftaran ditemukan dan apakah ada `bukti_pembayaran` yang tersimpan.
        if ($registration && $registration->bukti_pembayaran) {
            // Generate a public URL for the stored image
            $this->proofImageUrl = Storage::url($registration->bukti_pembayaran);
            // Jika bukti pembayaran ada, menghasilkan URL publik untuk file tersebut menggunakan Facade `Storage`.
            // URL ini kemudian disimpan di properti `$proofImageUrl` untuk ditampilkan di `<img>` di view.
            $this->showProofModal = true; // Open the payment proof modal
            // Mengatur `$showProofModal` menjadi `true` untuk menampilkan modal bukti pembayaran.
        } else {
            // Flash an error message if proof is not found
            // Jika bukti pembayaran tidak ditemukan atau tidak ada, mengirimkan pesan error ke session sebagai flash message.
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
    // Metode ini dipanggil ketika tombol "Terima" diklik untuk memverifikasi pendaftaran.
    // `$id` adalah ID pendaftaran yang akan diverifikasi.
    {
        // Find the registration or throw 404 if not found
        $registration = PendaftaranSantri::findOrFail($id);
        // Mencari data pendaftaran berdasarkan `$id`. Jika tidak ditemukan, Laravel akan secara otomatis melempar pengecualian `ModelNotFoundException` (menghasilkan error 404).

        // Update registration status and details
        // Memperbarui atribut-atribut model `$registration`.
        $registration->update([
            'status_santri' => 'diterima', // Set santri status to 'accepted'
            // Mengatur status santri menjadi 'diterima'.
            'status_pembayaran' => 'verified', // Set payment status to 'verified'
            // Mengatur status pembayaran menjadi 'verified'.
            'verified_at' => now(), // Record verification timestamp
            // Mencatat waktu verifikasi menggunakan helper `now()` Laravel (waktu saat ini).
            'verified_by' => auth()->id(), // Record user who verified
            // Mencatat ID pengguna yang melakukan verifikasi (pengguna yang sedang login).
        ]);

        // Flash a success message
        session()->flash('success', 'Pendaftaran ulang ' . $registration->nama_lengkap . ' telah diterima.');
        // Mengirimkan pesan sukses ke session untuk ditampilkan di halaman selanjutnya.
        $this->closeModal(); // Close any open modals
        // Memanggil `closeModal()` untuk memastikan modal detail (atau modal lainnya) tertutup setelah aksi berhasil.
        $this->dispatch('registrationVerified'); // Optional: dispatch an event for other components to listen
        // Mengirimkan event Livewire bernama 'registrationVerified'. Komponen Livewire lain dapat mendengarkan event ini dan bereaksi. (Opsional)
    }

    /**
     * Rejects a registration's payment proof, setting status to 'rejected' and clearing the proof.
     *
     * @param int $id The ID of the PendaftaranSantri whose payment proof is to be rejected.
     */
    public function rejectRegistration(int $id): void
    // Metode ini dipanggil ketika tombol "Tolak" diklik untuk menolak bukti pembayaran.
    // `$id` adalah ID pendaftaran yang bukti pembayarannya akan ditolak.
    {
        // Find the registration or throw 404 if not found
        $registration = PendaftaranSantri::findOrFail($id);
        // Mencari data pendaftaran berdasarkan `$id`.

        // Update registration status to 'rejected' and clear payment proof
        // Memperbarui atribut-atribut model `$registration`.
        $registration->update([
            'status_pembayaran' => 'rejected', // Set payment status to 'rejected'
            // Mengatur status pembayaran menjadi 'rejected'.
            'bukti_pembayaran' => null, // Clear the proof so santri can re-upload
            // Mengatur `bukti_pembayaran` menjadi `null`. Ini bertujuan agar santri bisa mengunggah ulang bukti pembayaran yang baru.
        ]);

        // Flash a success message
        session()->flash('success', 'Bukti pembayaran ' . $registration->nama_lengkap . ' ditolak.');
        // Mengirimkan pesan sukses ke session.
        $this->closeModal(); // Close any open modals
        // Memanggil `closeModal()` untuk menutup modal setelah aksi berhasil.
        $this->dispatch('registrationRejected'); // Optional: dispatch an event
        // Mengirimkan event Livewire bernama 'registrationRejected'. (Opsional)
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
    // Metode `render()` adalah jantung dari setiap komponen Livewire.
    // Metode ini bertanggung jawab untuk mengambil data dan merender tampilan (view) komponen.
    {
        $query = PendaftaranSantri::query(); // Start with a fresh query builder instance
        // Memulai query Eloquent untuk model `PendaftaranSantri`.

        // Filter by specific 'status_santri' (diterima or daftar_ulang)
        // Membatasi hasil hanya untuk santri yang berstatus 'diterima' atau 'daftar_ulang'.
        $query->whereIn('status_santri', ['diterima', 'daftar_ulang']);

        // Apply search filter if 'search' property is not empty
        // Menerapkan filter pencarian jika properti `$search` tidak kosong.
        $query->when($this->search, function ($q) {
            // `when()` adalah helper Eloquent yang menjalankan callback jika kondisi pertama (`$this->search` bernilai true/tidak kosong) terpenuhi.
            $q->where(function ($subQuery) {
                // Menggunakan sub-query `where` untuk mengelompokkan kondisi OR.
                // Search across 'nama_lengkap' or 'nisn'
                $subQuery->where('nama_lengkap', 'like', '%' . $this->search . '%')
                         // Mencari nama lengkap yang mengandung `$this->search` (case-insensitive di banyak database).
                         ->orWhere('nisn', 'like', '%' . $this->search . '%');
                         // Atau mencari NISN yang mengandung `$this->search`.
            });
        });

        // Apply 'tipe_pendaftaran' filter if 'filters.tipe' is set
        // Menerapkan filter berdasarkan 'tipe_pendaftaran' jika `filters.tipe` tidak kosong.
        $query->when($this->filters['tipe'], function ($q) {
            $q->where('tipe_pendaftaran', $this->filters['tipe']);
        });

        // Apply 'status_pembayaran' filter if 'filters.status' is set
        // Menerapkan filter berdasarkan 'status_pembayaran' jika `filters.status` tidak kosong.
        $query->when($this->filters['status'], function ($q) {
            if ($this->filters['status'] === 'no_proof') {
                // Logika khusus untuk status 'no_proof' (belum ada bukti pembayaran).
                $q->whereNull('bukti_pembayaran'); // Filter for registrations with no payment proof
                // Menambahkan kondisi `whereNull` untuk mencari data yang kolom `bukti_pembayaran`-nya adalah NULL.
            } else {
                $q->where('status_pembayaran', $this->filters['status']);
                // Untuk status pembayaran lainnya (pending, verified, rejected), filter langsung berdasarkan nilai status.
            }
        });

        // Apply ordering based on 'filters.urutan' or default sort field/direction
        // Menerapkan pengurutan data.
        if ($this->filters['urutan'] === 'terbaru') {
            // Jika filter 'urutan' adalah 'terbaru', urutkan berdasarkan `created_at` secara descending.
            $query->orderBy('created_at', 'desc');
        } elseif ($this->filters['urutan'] === 'terlama') {
            // Jika filter 'urutan' adalah 'terlama', urutkan berdasarkan `created_at` secara ascending.
            $query->orderBy('created_at', 'asc');
        } else {
            // Fallback to general sortField and sortDirection
            // Jika filter 'urutan' tidak spesifik, gunakan `$sortField` dan `$sortDirection` yang ditentukan dari sorting kolom.
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        // Paginate the results and pass them to the view
        // Melakukan paginasi hasil query dengan jumlah item per halaman yang ditentukan oleh `$this->perPage`.
        return view('livewire.admin.psb.dashboard-daftar-ulang', [
            // Mengembalikan view Blade `livewire.admin.psb.dashboard-daftar-ulang`.
            'registrations' => $query->paginate($this->perPage),
            // Data hasil paginasi disimpan dalam variabel `registrations` yang akan tersedia di view.
        ]);
    }
}