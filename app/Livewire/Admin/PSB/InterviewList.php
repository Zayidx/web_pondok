<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;                  // Mengimpor kelas utama Livewire
use Livewire\WithPagination;             // Mengimpor fitur paginasi dari Livewire
use App\Models\PSB\PendaftaranSantri;    // Mengimpor model untuk data pendaftaran santri
use App\Models\PSB\JadwalWawancara;      // Mengimpor model untuk data jadwal wawancara
use Illuminate\Support\Facades\DB;       // Mengimpor DB untuk transaksi database
use Illuminate\Support\Facades\Log;      // Mengimpor Log untuk mencatat error

class InterviewList extends Component
{
    use WithPagination;  // Menggunakan fitur paginasi untuk menampilkan data secara berhalaman

    public $perPage = 5;                // Jumlah item per halaman (default 5)
    public $search = '';                // Variabel untuk pencarian
    public $tanggalWawancara = '';      // Variabel untuk filter berdasarkan tanggal wawancara
    public $jamWawancara = '';          // Variabel untuk filter berdasarkan jam wawancara
    public $lokasiWawancara = '';       // Variabel untuk filter berdasarkan lokasi wawancara
    public $sortField = 'created_at';   // Kolom default untuk pengurutan
    public $sortDirection = 'desc';     // Arah pengurutan default (menurun)
    public $editInterviewModal = false; // Status untuk membuka/tutup modal edit
    public $rejectModal = false;        // Status untuk membuka/tutup modal penolakan
    public $selectedInterviewId;        // ID wawancara yang dipilih
    public $selectedSantriId;           // ID santri yang dipilih

    /**
     * Data formulir untuk mengedit jadwal wawancara
     * - tanggal_wawancara: tanggal wawancara
     * - jam_wawancara: jam wawancara
     * - mode: mode wawancara (offline/online)
     * - link_online: link untuk wawancara online
     * - lokasi_offline: lokasi untuk wawancara offline
     */
    public $editInterviewForm = [
        'tanggal_wawancara' => '',
        'jam_wawancara' => '',
        'mode' => 'offline',
        'link_online' => '',
        'lokasi_offline' => '',
    ];

    /**
     * Data formulir untuk alasan penolakan
     * - reason: alasan penolakan santri
     */
    public $rejectForm = [
        'reason' => '',
    ];

    /**
     * Mengatur parameter yang dapat disimpan di URL
     * - perPage, search, dll. akan dipertahankan saat navigasi halaman
     */
    protected $queryString = [
        'perPage' => ['except' => 5],
        'search' => ['except' => ''],
        'tanggalWawancara' => ['except' => ''],
        'jamWawancara' => ['except' => ''],
        'lokasiWawancara' => ['except' => ''],
    ];

    /**
     * Mereset halaman saat pencarian diperbarui
     * - Memastikan paginasi kembali ke halaman pertama
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Mereset halaman saat filter tanggal wawancara diperbarui
     * - Memastikan paginasi kembali ke halaman pertama
     */
    public function updatingTanggalWawancara()
    {
        $this->resetPage();
    }

    /**
     * Mereset halaman saat filter jam wawancara diperbarui
     * - Memastikan paginasi kembali ke halaman pertama
     */
    public function updatingJamWawancara()
    {
        $this->resetPage();
    }

    /**
     * Mereset halaman saat filter lokasi wawancara diperbarui
     * - Memastikan paginasi kembali ke halaman pertama
     */
    public function updatingLokasiWawancara()
    {
        $this->resetPage();
    }

    /**
     * Mereset halaman saat jumlah item per halaman diperbarui
     * - Memastikan paginasi kembali ke halaman pertama
     */
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    /**
     * Mengatur pengurutan data
     * - Jika kolom yang sama diklik, ubah arah pengurutan (asc/desc)
     * - Jika kolom baru diklik, set arah pengurutan ke asc
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Membatalkan penerimaan santri
     * - Mengupdate status santri menjadi null
     * - Menghapus jadwal wawancara, data santri, dan data orang tua terkait
     * - Menampilkan pesan sukses atau error
     */
    public function cancelAcceptance($santriId)
    {
        DB::beginTransaction();  // Memulai transaksi database
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            $santri->update(['status_santri' => null, 'reason_rejected' => null]);  // Mengosongkan status dan alasan penolakan

            // Menghapus jadwal wawancara terkait
            JadwalWawancara::where('santri_id', $santriId)->delete();

            // Menghapus data santri dan orang tua dari tabel terkait
            \App\Models\Santri::where('nisn', $santri->nisn)->delete();
            \App\Models\OrangTuaSantri::where('santri_id', $santri->id)->delete();

            DB::commit();  // Menyelesaikan transaksi jika sukses
            session()->flash('success', 'Status santri dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();  // Membatalkan transaksi jika gagal
            Log::error('Error in cancelAcceptance: ' . $e->getMessage());  // Mencatat error
            session()->flash('error', 'Gagal membatalkan: ' . $e->getMessage());
        }
    }

    /**
     * Membuka modal untuk mengedit jadwal wawancara
     * - Mengambil data wawancara berdasarkan ID
     * - Mengisi formulir edit dengan data yang ada
     * - Membuka modal dan mereset validasi
     */
    public function openEditInterviewModal($interviewId)
    {
        $interview = JadwalWawancara::findOrFail($interviewId);
        $this->selectedInterviewId = $interviewId;
        $this->editInterviewForm = [
            'tanggal_wawancara' => $interview->tanggal_wawancara,
            'jam_wawancara' => $interview->jam_wawancara,
            'mode' => $interview->mode,
            'link_online' => $interview->link_online,
            'lokasi_offline' => $interview->lokasi_offline,
        ];
        $this->editInterviewModal = true;
        $this->resetValidation();
    }

    /**
     * Menutup semua modal
     * - Menutup modal edit wawancara dan penolakan
     */
    public function closeModal()
    {
        $this->editInterviewModal = false;
        $this->rejectModal = false;
    }

    /**
     * Memperbarui jadwal wawancara
     * - Memvalidasi input (tanggal harus setelah hari ini, mode wajib, dll.)
     * - Mengupdate data wawancara di database
     * - Menutup modal dan menampilkan pesan sukses atau error
     */
    public function updateInterview()
    {
        $this->validate([
            'editInterviewForm.tanggal_wawancara' => 'required|date|after:today',  // Tanggal wajib dan harus setelah hari ini
            'editInterviewForm.jam_wawancara' => 'required',                       // Jam wajib diisi
            'editInterviewForm.mode' => 'required|in:online,offline',              // Mode wajib, hanya online/offline
            'editInterviewForm.link_online' => 'required_if:editInterviewForm.mode,online|url|nullable',  // Link wajib jika mode online
            'editInterviewForm.lokasi_offline' => 'required_if:editInterviewForm.mode,offline|nullable',  // Lokasi wajib jika mode offline
        ]);

        try {
            $interview = JadwalWawancara::findOrFail($this->selectedInterviewId);
            $interview->update([
                'tanggal_wawancara' => $this->editInterviewForm['tanggal_wawancara'],
                'jam_wawancara' => $this->editInterviewForm['jam_wawancara'],
                'mode' => $this->editInterviewForm['mode'],
                'link_online' => $this->editInterviewForm['link_online'],
                'lokasi_offline' => $this->editInterviewForm['lokasi_offline'],
            ]);

            $this->editInterviewModal = false;
            session()->flash('success', 'Jadwal wawancara berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error in updateInterview: ' . $e->getMessage());  // Mencatat error
            session()->flash('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    /**
     * Membuka modal untuk menolak santri
     * - Menyimpan ID santri yang dipilih
     * - Mengosongkan alasan penolakan dan membuka modal
     * - Mereset validasi sebelumnya
     */
    public function openRejectModal($santriId)
    {
        $this->selectedSantriId = $santriId;
        $this->rejectForm = ['reason' => ''];
        $this->rejectModal = true;
        $this->resetValidation();
    }

    /**
     * Proses penolakan santri
     * - Memvalidasi alasan penolakan (wajib diisi, maksimal 500 karakter)
     * - Mengupdate status santri menjadi 'ditolak' dan menyimpan alasan
     * - Menghapus jadwal wawancara, data santri, dan data orang tua terkait
     * - Menampilkan pesan sukses atau error
     */
    public function reject()
    {
        $this->validate([
            'rejectForm.reason' => 'required|string|max:500',  // Alasan wajib, maksimal 500 karakter
        ]);

        DB::beginTransaction();  // Memulai transaksi database
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);
            $santri->update([
                'status_santri' => 'ditolak',
                'reason_rejected' => $this->rejectForm['reason'],  // Menyimpan alasan penolakan
            ]);

            // Menghapus jadwal wawancara terkait
            JadwalWawancara::where('santri_id', $santri->id)->delete();

            // Menghapus data santri dan orang tua dari tabel terkait
            \App\Models\Santri::where('nisn', $santri->nisn)->delete();
            \App\Models\OrangTuaSantri::where('santri_id', $santri->id)->delete();

            DB::commit();  // Menyelesaikan transaksi jika sukses
            $this->rejectModal = false;
            session()->flash('success', 'Santri ditolak dengan alasan yang diberikan.');
        } catch (\Exception $e) {
            DB::rollBack();  // Membatalkan transaksi jika gagal
            Log::error('Error in reject: ' . $e->getMessage());  // Mencatat error
            session()->flash('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan daftar jadwal wawancara
     * - Mengambil data santri dengan status 'diterima'
     * - Mendukung filter berdasarkan pencarian, tanggal, jam, dan lokasi
     * - Mengurutkan data dan membaginya per halaman
     */
    public function render()
    {
        $interviews = PendaftaranSantri::query()
            ->where('status_santri', 'diterima')  // Hanya menampilkan santri yang diterima
            ->with('jadwalWawancara')             // Mengambil relasi jadwal wawancara
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');  // Pencarian berdasarkan nama atau NISN
            })
            ->when($this->tanggalWawancara, function ($query) {
                $query->whereHas('jadwalWawancara', function ($q) {
                    $q->where('tanggal_wawancara', $this->tanggalWawancara);  // Filter berdasarkan tanggal
                });
            })
            ->when($this->jamWawancara, function ($query) {
                $query->whereHas('jadwalWawancara', function ($q) {
                    $q->where('jam_wawancara', $this->jamWawancara);  // Filter berdasarkan jam
                });
            })
            ->when($this->lokasiWawancara, function ($query) {
                $query->whereHas('jadwalWawancara', function ($q) {
                    $q->where('lokasi_offline', 'like', '%' . $this->lokasiWawancara . '%')
                      ->orWhere('link_online', 'like', '%' . $this->lokasiWawancara . '%');  // Filter berdasarkan lokasi
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)  // Mengurutkan berdasarkan kolom dan arah yang dipilih
            ->paginate($this->perPage);  // Membagi data per halaman

        return view('livewire.admin.psb.interview-list', [
            'interviews' => $interviews,  // Mengirim data ke view
        ]);
    }
}