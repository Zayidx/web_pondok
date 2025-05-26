<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ShowRegistrations extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $search = '';
    public $kewarganegaraan = '';
    public $kota = '';
    public $status_santri = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $interviewModal = false;
    public $rejectModal = false;
    public $selectedSantriId;
    public $interviewForm = [
        'tanggal_wawancara' => '',
        'jam_wawancara' => '',
        'mode' => 'offline',
        'link_online' => '',
        'lokasi_offline' => '',
    ];
    public $rejectForm = [
        'reason' => '',
    ];

    protected $queryString = [
        'perPage' => ['except' => 5],
        'search' => ['except' => ''],
        'kewarganegaraan' => ['except' => ''],
        'kota' => ['except' => ''],
        'status_santri' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKewarganegaraan()
    {
        Log::info('Kewarganegaraan updated to: ' . $this->kewarganegaraan);
        $this->resetPage();
    }

    public function updatingKota()
    {
        $this->resetPage();
    }

    public function updatingStatusSantri()
    {
        Log::info('Status santri updated to: ' . $this->status_santri);
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        Log::info('PerPage updated to: ' . $this->perPage);
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

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

    public function closeModal()
    {
        $this->interviewModal = false;
        $this->rejectModal = false;
    }

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

    public function openRejectModal($santriId)
    {
        $this->selectedSantriId = $santriId;
        $this->rejectForm = ['reason' => ''];
        $this->rejectModal = true;
        $this->resetValidation();
    }

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

    public function render()
    {
        $registrations = PendaftaranSantri::query()
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%');
            })
            ->when($this->kewarganegaraan, function ($query) {
                $query->where('kewarganegaraan', $this->kewarganegaraan);
            })
            ->when($this->kota, function ($query) {
                $query->whereHas('wali', function ($q) {
                    $q->where('kabupaten', 'like', '%' . $this->kota . '%');
                });
            })
            ->when($this->status_santri, function ($query) {
                $query->where('status_santri', $this->status_santri);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

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