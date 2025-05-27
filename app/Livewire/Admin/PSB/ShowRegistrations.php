<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;                  // Mengimpor kelas utama Livewire
use Livewire\WithPagination;             // Mengimpor fitur paginasi dari Livewire
use App\Models\PSB\PendaftaranSantri;    // Mengimpor model untuk data pendaftaran santri
use App\Models\PSB\WaliSantri;           // Mengimpor model untuk data wali santri
use Illuminate\Support\Facades\DB;       // Mengimpor DB untuk transaksi database
use Illuminate\Support\Facades\Log;      // Mengimpor Log untuk mencatat error
use Carbon\Carbon;                       // Mengimpor Carbon untuk manipulasi tanggal

class ShowRegistrations extends Component
{
    use WithPagination;  // Menggunakan fitur paginasi untuk menampilkan data secara berhalaman

    public $perPage = 5;                // Jumlah item per halaman (default 5)
    public $search = '';                // Variabel untuk pencarian nama atau NISN
    public $kewarganegaraan = '';       // Variabel untuk filter berdasarkan kewarganegaraan
    public $kota = '';                  // Variabel untuk filter berdasarkan kota
    public $status_santri = '';         // Variabel untuk filter berdasarkan status santri
    public $sortField = 'created_at';   // Kolom default untuk pengurutan
    public $sortDirection = 'desc';     // Arah pengurutan default (menurun)
    public $interviewModal = false;     // Status untuk membuka/tutup modal jadwal wawancara
    public $rejectModal = false;        // Status untuk membuka/tutup modal penolakan
    public $selectedSantriId;           // ID santri yang dipilih untuk aksi

    /**
     * Data formulir untuk jadwal wawancara
     * - tanggal_wawancara: tanggal wawancara
     * - jam_wawancara: jam wawancara
     * - mode: mode wawancara (online/offline)
     * - link_online: link untuk wawancara online
     * - lokasi_offline: lokasi untuk wawancara offline
     */
    public $interviewForm = [
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
        'kewarganegaraan' => ['except' => ''],
        'kota' => ['except' => ''],
        'status_santri' => ['except' => ''],
    ];

    /**
     * Mereset halaman saat pencarian diperbarui
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Mereset halaman saat filter kewarganegaraan diperbarui
     * - Mencatat perubahan ke log
     */
    public function updatingKewarganegaraan()
    {
        Log::info('Kewarganegaraan updated to: ' . $this->kewarganegaraan);
        $this->resetPage();
    }

    /**
     * Mereset halaman saat filter kota diperbarui
     */
    public function updatingKota()
    {
        $this->resetPage();
    }

    /**
     * Mereset halaman saat filter status santri diperbarui
     * - Mencatat perubahan ke log
     */
    public function updatingStatusSantri()
    {
        Log::info('Status santri updated to: ' . $this->status_santri);
        $this->resetPage();
    }

    /**
     * Mereset halaman saat jumlah item per halaman diperbarui
     * - Mencatat perubahan ke log
     */
    public function updatingPerPage()
    {
        Log::info('PerPage updated to: ' . $this->perPage);
        $this->resetPage();
    }

    /**
     * Mengatur pengurutan data
     * - Jika kolom yang sama diklik, ubah arah pengurutan (asc/desc)
     * - Jika kolom berbeda, gunakan arah ascending secara default
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
     * Membuka modal untuk menjadwalkan wawancara
     * - Menyimpan ID santri yang dipilih
     * - Mengosongkan formulir wawancara dan membuka modal
     * - Mereset validasi sebelumnya
     */
    public function openInterviewModal($santriId)
    {
        Log::info('openInterviewModal called with santriId: ' . $santriId);
        $this->selectedSantriId = $santriId;
        $this->interviewForm = [
            'tanggal_wawancara' => '',
            'jam_wawancara' => '',
            'mode' => 'offline',
            'link_online' => '',
            'lokasi_offline' => '',
        ];
        $this->interviewModal = true;
        $this->resetValidation();
    }

    /**
     * Menutup semua modal yang aktif
     * - Menutup modal wawancara dan penolakan
     */
    public function closeModal()
    {
        $this->interviewModal = false;
        $this->rejectModal = false;
    }

    /**
     * Menyimpan jadwal wawancara dan mengupdate status santri
     * - Memvalidasi input wawancara
     * - Mengupdate status santri menjadi 'diterima'
     * - Menyimpan jadwal wawancara dan memindahkan data ke tabel santri
     */
    public function saveInterview()
    {
        Log::info('saveInterview called with data: ', $this->interviewForm);

        $this->validate([
            'interviewForm.tanggal_wawancara' => 'required|date|after:today',
            'interviewForm.jam_wawancara' => 'required',
            'interviewForm.mode' => 'required|in:online,offline',
            'interviewForm.link_online' => 'required_if:interviewForm.mode,online|url|nullable',
            'interviewForm.lokasi_offline' => 'required_if:interviewForm.mode,offline|nullable',
        ]);

        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);
            Log::info('Updating status_santri to diterima for santri ID: ' . $santri->id);
            $santri->update(['status_santri' => 'diterima']);

            \App\Models\PSB\JadwalWawancara::create([
                'santri_id' => $santri->id,
                'tanggal_wawancara' => $this->interviewForm['tanggal_wawancara'],
                'jam_wawancara' => $this->interviewForm['jam_wawancara'],
                'mode' => $this->interviewForm['mode'],
                'link_online' => $this->interviewForm['link_online'],
                'lokasi_offline' => $this->interviewForm['lokasi_offline'],
            ]);

            $this->moveToSantri($santri);

            DB::commit();
            $this->interviewModal = false;
            $this->resetValidation();
            session()->flash('success', 'Santri diterima dan jadwal wawancara disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in saveInterview: ' . $e->getMessage());
            $this->interviewModal = false;
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
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
     */
    public function reject()
    {
        $this->validate([
            'rejectForm.reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($this->selectedSantriId);
            $santri->update([
                'status_santri' => 'ditolak',
                'reason_rejected' => $this->rejectForm['reason'],
            ]);

            // Hapus jadwal wawancara jika ada
            \App\Models\PSB\JadwalWawancara::where('santri_id', $santri->id)->delete();
            // Hapus data dari tabel santri dan orang tua santri jika ada
            \App\Models\Santri::where('nisn', $santri->nisn)->delete();
            \App\Models\OrangTuaSantri::where('santri_id', $santri->id)->delete();

            DB::commit();
            $this->rejectModal = false;
            session()->flash('success', 'Santri ditolak dengan alasan yang diberikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in reject: ' . $e->getMessage());
            session()->flash('error', 'Gagal menolak santri: ' . $e->getMessage());
        }
    }

    /**
     * Membatalkan status santri (diterima/ditolak)
     * - Mengatur ulang status santri menjadi null
     * - Menghapus jadwal wawancara, data santri, dan data orang tua terkait
     */
    public function cancelStatus($santriId)
    {
        DB::beginTransaction();
        try {
            $santri = PendaftaranSantri::findOrFail($santriId);
            $santri->update(['status_santri' => null, 'reason_rejected' => null]);

            // Hapus jadwal wawancara jika ada
            \App\Models\PSB\JadwalWawancara::where('santri_id', $santri->id)->delete();
            // Hapus data dari tabel santri dan orang tua santri jika ada
            \App\Models\Santri::where('nisn', $santri->nisn)->delete();
            \App\Models\OrangTuaSantri::where('santri_id', $santri->id)->delete();

            DB::commit();
            session()->flash('success', 'Status santri dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in cancelStatus: ' . $e->getMessage());
            session()->flash('error', 'Gagal membatalkan status: ' . $e->getMessage());
        }
    }

    /**
     * Memindahkan data santri ke tabel utama setelah diterima
     * - Membuat entri baru di tabel Santri dan OrangTuaSantri
     * - Menggunakan data dari PendaftaranSantri dan WaliSantri
     */
    protected function moveToSantri($santri)
    {
        $wali = WaliSantri::where('pendaftaran_santri_id', $santri->id)->first();
        if (!$wali) {
            Log::error('Wali not found for santri ID: ' . $santri->id);
            throw new \Exception('Data wali tidak ditemukan.');
        }

        $newSantri = \App\Models\Santri::create([
            'nama' => $santri->nama_lengkap,
            'nisn' => $santri->nisn,
            'nism' => $santri->nism,
            'npsn' => $santri->npsn,
            'no_kip' => $santri->kip,
            'no_kk' => $santri->no_kk,
            'jumlah_saudara_kandung' => $santri->jumlah_saudara_kandung,
            'anak_ke' => $santri->anak_keberapa,
            'jenis_kelamin' => $santri->jenis_kelamin,
            'tanggal_lahir' => $santri->tanggal_lahir,
            'tempat_lahir' => $santri->tempat_lahir,
            'asal_sekolah' => $santri->asal_sekolah,
            'no_whatsapp' => $santri->no_whatsapp,
            'email' => $santri->email,
            'status_santri' => $santri->status_santri,
            'kewarganegaraan' => $santri->kewarganegaraan,
            'kelas_id' => \App\Models\Kelas::where('nama', $santri->kelas)->first()->id ?? null,
            'kamar_id' => \App\Models\Kamar::first()->id ?? null,
            'pembiayaan' => $santri->pembiayaan,
            'riwayat_penyakit' => $santri->riwayat_penyakit,
            'hobi' => $santri->hobi,
            'aktivitas_pendidikan' => $santri->aktivitas_pendidikan,
            'nik' => $santri->nik,
            'status_kesantrian' => $santri->status_kesantrian,
            'foto' => $santri->foto,
            'nama_kepala_keluarga' => $wali->nama_kepala_keluarga ?? null,
            'no_hp_kepala_keluarga' => $wali->no_hp_kepala_keluarga ?? null,
        ]);

        \App\Models\OrangTuaSantri::create([
            'santri_id' => $newSantri->id,
            'nama_ayah' => $wali->nama_ayah,
            'status_ayah' => $wali->status_ayah,
            'kewarganegaraan_ayah' => $wali->kewarganegaraan_ayah,
            'nik_ayah' => $wali->nik_ayah,
            'tempat_lahir_ayah' => $wali->tempat_lahir_ayah,
            'tanggal_lahir_ayah' => $wali->tanggal_lahir_ayah,
            'pendidikan_terakhir_ayah' => $wali->pendidikan_terakhir_ayah,
            'pekerjaan_ayah' => $wali->pekerjaan_ayah,
            'penghasilan_ayah' => $wali->penghasilan_ayah,
            'no_telp_ayah' => $wali->no_telp_ayah,
            'nama_ibu' => $wali->nama_ibu,
            'status_ibu' => $wali->status_ibu,
            'kewarganegaraan_ibu' => $wali->kewarganegaraan_ibu,
            'nik_ibu' => $wali->nik_ibu,
            'tempat_lahir_ibu' => $wali->tempat_lahir_ibu,
            'tanggal_lahir_ibu' => $wali->tanggal_lahir_ibu,
            'pendidikan_terakhir_ibu' => $wali->pendidikan_terakhir_ibu,
            'pekerjaan_ibu' => $wali->pekerjaan_ibu,
            'penghasilan_ibu' => $wali->penghasilan_ibu,
            'no_telp_ibu' => $wali->no_telp_ibu,
            'status_orang_tua' => $wali->status_orang_tua,
            'provinsi' => $wali->provinsi,
            'kabupaten' => $wali->kabupaten,
            'kecamatan' => $wali->kecamatan,
            'kelurahan' => $wali->kelurahan,
            'rt' => $wali->rt,
            'rw' => $wali->rw,
            'kode_pos' => $wali->kode_pos,
            'status_kepemilikan_rumah' => $wali->status_kepemilikan_rumah,
            'alamat' => $wali->alamat,
        ]);
    }

    /**
     * Menampilkan daftar pendaftaran santri
     * - Mendukung pencarian, filter, dan pengurutan
     * - Mengambil data santri dengan relasi wali
     * - Menyediakan opsi untuk kewarganegaraan dan status santri
     */
    public function render()
    {
        $registrations = PendaftaranSantri::query()
            ->with('wali')  // Mengambil relasi wali untuk filter kota
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');  // Pencarian berdasarkan nama atau NISN
            })
            ->when($this->kewarganegaraan, function ($query) {
                $query->where('kewarganegaraan', $this->kewarganegaraan);  // Filter berdasarkan kewarganegaraan
            })
            ->when($this->kota, function ($query) {
                $query->whereHas('wali', function ($q) {
                    $q->where('kabupaten', 'like', '%' . $this->kota . '%');  // Filter berdasarkan kota
                });
            })
            ->when($this->status_santri, function ($query) {
                $query->where('status_santri', $this->status_santri);  // Filter berdasarkan status santri
            })
            ->orderBy($this->sortField, $this->sortDirection)  // Mengurutkan berdasarkan kolom dan arah
            ->paginate($this->perPage);  // Membagi data per halaman

        return view('livewire.admin.psb.show-registrations', [
            'registrations' => $registrations,
            'kewarganegaraanOptions' => ['wni' => 'WNI', 'wna' => 'WNA'],
            'statusSantriOptions' => [
                'reguler' => 'Reguler',
                'dhuafa' => 'Dhuafa',
                'yatim_piatu' => 'Yatim Piatu',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak',
            ],
        ]);
    }
}