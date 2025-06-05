<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

class EditRegistration extends Component
{
    use WithFileUploads;

    #[Title('Edit Data Santri PPDB')]
    public $santriId;
    public $santri;
    public $formPage = 1;
    public $foto;
    public $fotoSantri;
    public $dokumenBaru = [];
    public $editForm = [
        'nama_lengkap' => '',
        'nisn' => '',
        'tipe_pendaftaran' => '',
        'jenis_kelamin' => '',
        'tempat_lahir' => '',
        'tanggal_lahir' => '',
        'nik' => '',
        'no_kk' => '',
        'alamat' => '',
        'desa' => '',
        'kecamatan' => '',
        'kabupaten' => '',
        'provinsi' => '',
        'kode_pos' => '',
        'no_hp' => '',
        'email' => '',
        'asal_sekolah' => '',
        'tahun_lulus' => '',
        'agama' => '',
        'no_whatsapp' => '',
        'status_santri' => '',
        // Data Wali
        'nama_ayah' => '',
        'pekerjaan_ayah' => '',
        'pendidikan_ayah' => '',
        'penghasilan_ayah' => '',
        'nama_ibu' => '',
        'pekerjaan_ibu' => '',
        'pendidikan_ibu' => '',
        'no_telp_ibu' => '',
    ];

    public function mount($santriId)
    {
        $this->santriId = $santriId;
        $this->loadSantri();
    }

    public function loadSantri()
    {
        try {
            $this->santri = PendaftaranSantri::with(['wali', 'dokumen'])->findOrFail($this->santriId);
            $this->fotoSantri = $this->santri->dokumen->where('jenis_berkas', 'Pas Foto')->first()?->file_path;
            
            $this->editForm = [
                'nama_lengkap' => $this->santri->nama_lengkap,
                'nisn' => $this->santri->nisn,
                'tipe_pendaftaran' => $this->santri->tipe_pendaftaran,
                'jenis_kelamin' => $this->santri->jenis_kelamin,
                'tempat_lahir' => $this->santri->tempat_lahir,
                'tanggal_lahir' => $this->santri->tanggal_lahir,
                'nik' => $this->santri->nik,
                'no_kk' => $this->santri->no_kk,
                'alamat' => $this->santri->wali->alamat ?? '',
                'desa' => $this->santri->wali->desa ?? '',
                'kecamatan' => $this->santri->wali->kecamatan ?? '',
                'kabupaten' => $this->santri->wali->kabupaten ?? '',
                'provinsi' => $this->santri->wali->provinsi ?? '',
                'kode_pos' => $this->santri->wali->kode_pos ?? '',
                'no_hp' => $this->santri->wali->no_hp ?? '',
                'email' => $this->santri->wali->email ?? '',
                'asal_sekolah' => $this->santri->asal_sekolah ?? '',
                'tahun_lulus' => $this->santri->tahun_lulus ?? '',
                'agama' => $this->santri->agama ?? '',
                'no_whatsapp' => $this->santri->no_whatsapp ?? '',
                'status_santri' => $this->santri->status_santri ?? '',
                // Data Wali
                'nama_ayah' => $this->santri->wali->nama_ayah ?? '',
                'pekerjaan_ayah' => $this->santri->wali->pekerjaan_ayah ?? '',
                'pendidikan_ayah' => $this->santri->wali->pendidikan_ayah ?? '',
                'penghasilan_ayah' => $this->santri->wali->penghasilan_ayah ?? '',
                'nama_ibu' => $this->santri->wali->nama_ibu ?? '',
                'pekerjaan_ibu' => $this->santri->wali->pekerjaan_ibu ?? '',
                'pendidikan_ibu' => $this->santri->wali->pendidikan_ibu ?? '',
                'no_telp_ibu' => $this->santri->wali->no_telp_ibu ?? '',
            ];
        } catch (\Exception $e) {
            Log::error('Error loading santri: ' . $e->getMessage());
            session()->flash('error', 'Data santri tidak ditemukan.');
            return redirect()->route('admin.master-psb.show-registrations');
        }
    }

    public function prevForm()
    {
        if ($this->formPage > 1) {
            $this->formPage--;
        }
    }

    public function nextForm()
    {
        if ($this->formPage < 3) {
            $this->formPage++;
        }
    }

    public function updatedFoto()
    {
        $this->validate([
            'foto' => 'image|max:2048', // 2MB Max
        ]);
    }

    public function updatedDokumenBaru()
    {
        $this->validate([
            'dokumenBaru.*' => 'file|max:2048', // 2MB Max
        ]);
    }

    public function save()
    {
        $this->validate([
            'editForm.nama_lengkap' => 'required|string|max:255',
            'editForm.nisn' => 'required|string|max:20',
            'editForm.tipe_pendaftaran' => 'required|in:reguler,olimpiade,internasional',
            'editForm.jenis_kelamin' => 'required|in:L,P',
            'editForm.tempat_lahir' => 'required|string|max:100',
            'editForm.tanggal_lahir' => 'required|date',
            'editForm.nik' => 'required|string|size:16',
            'editForm.no_kk' => 'required|string|size:16',
            'editForm.alamat' => 'required|string|max:255',
            'editForm.desa' => 'required|string|max:100',
            'editForm.kecamatan' => 'required|string|max:100',
            'editForm.kabupaten' => 'required|string|max:100',
            'editForm.provinsi' => 'required|string|max:100',
            'editForm.kode_pos' => 'required|string|max:5',
            'editForm.no_hp' => 'required|string|max:15',
            'editForm.email' => 'required|email|max:255',
            'editForm.asal_sekolah' => 'required|string|max:255',
            'editForm.tahun_lulus' => 'required|string|max:4',
            'editForm.agama' => 'required|string|max:50',
            'editForm.no_whatsapp' => 'required|string|max:15',
            'editForm.status_santri' => 'required|in:menunggu,wawancara,diterima,ditolak',
            // Data Wali
            'editForm.nama_ayah' => 'required|string|max:255',
            'editForm.pekerjaan_ayah' => 'required|string|max:100',
            'editForm.pendidikan_ayah' => 'required|string|max:50',
            'editForm.penghasilan_ayah' => 'required|string|max:50',
            'editForm.nama_ibu' => 'required|string|max:255',
            'editForm.pekerjaan_ibu' => 'required|string|max:100',
            'editForm.pendidikan_ibu' => 'required|string|max:50',
            'editForm.no_telp_ibu' => 'required|string|max:15',
            'foto' => 'nullable|image|max:2048',
            'dokumenBaru.*' => 'nullable|file|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Upload foto if provided
            if ($this->foto) {
                $fotoPath = $this->foto->store('public/santri/foto');
                // Create or update Pas Foto document
                $this->santri->dokumen()->updateOrCreate(
                    ['jenis_berkas' => 'Pas Foto'],
                    [
                        'file_path' => str_replace('public/', '', $fotoPath),
                        'tanggal' => now(),
                    ]
                );
            }

            $this->santri->update([
                'nama_lengkap' => $this->editForm['nama_lengkap'],
                'nisn' => $this->editForm['nisn'],
                'tipe_pendaftaran' => $this->editForm['tipe_pendaftaran'],
                'jenis_kelamin' => $this->editForm['jenis_kelamin'],
                'tempat_lahir' => $this->editForm['tempat_lahir'],
                'tanggal_lahir' => $this->editForm['tanggal_lahir'],
                'nik' => $this->editForm['nik'],
                'no_kk' => $this->editForm['no_kk'],
                'asal_sekolah' => $this->editForm['asal_sekolah'],
                'tahun_lulus' => $this->editForm['tahun_lulus'],
                'agama' => $this->editForm['agama'],
                'no_whatsapp' => $this->editForm['no_whatsapp'],
                'status_santri' => $this->editForm['status_santri'],
            ]);

            if ($this->santri->wali) {
                $this->santri->wali->update([
                    'alamat' => $this->editForm['alamat'],
                    'desa' => $this->editForm['desa'],
                    'kecamatan' => $this->editForm['kecamatan'],
                    'kabupaten' => $this->editForm['kabupaten'],
                    'provinsi' => $this->editForm['provinsi'],
                    'kode_pos' => $this->editForm['kode_pos'],
                    'no_hp' => $this->editForm['no_hp'],
                    'email' => $this->editForm['email'],
                    'nama_ayah' => $this->editForm['nama_ayah'],
                    'pekerjaan_ayah' => $this->editForm['pekerjaan_ayah'],
                    'pendidikan_ayah' => $this->editForm['pendidikan_ayah'],
                    'penghasilan_ayah' => $this->editForm['penghasilan_ayah'],
                    'nama_ibu' => $this->editForm['nama_ibu'],
                    'pekerjaan_ibu' => $this->editForm['pekerjaan_ibu'],
                    'pendidikan_ibu' => $this->editForm['pendidikan_ibu'],
                    'no_telp_ibu' => $this->editForm['no_telp_ibu'],
                ]);
            }

            // Handle new documents
            $documentTypes = [
                'kk' => 'Kartu Keluarga',
                'akta' => 'Akta Kelahiran',
                'ijazah' => 'Ijazah',
            ];

            foreach ($this->dokumenBaru as $type => $file) {
                if (isset($documentTypes[$type])) {
                    $path = $file->store('public/santri/dokumen');
                    $this->santri->dokumen()->updateOrCreate(
                        ['jenis_berkas' => $documentTypes[$type]],
                        [
                            'file_path' => str_replace('public/', '', $path),
                            'tanggal' => now(),
                        ]
                    );
                }
            }

            DB::commit();
            session()->flash('success', 'Data santri berhasil diperbarui.');
            return redirect()->route('admin.master-psb.show-registrations');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating santri: ' . $e->getMessage());
            session()->flash('error', 'Gagal memperbarui data santri: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.psb.edit-registration', [
            'tipeOptions' => [
                'reguler' => 'Reguler',
                'olimpiade' => 'Olimpiade',
                'internasional' => 'Internasional',
            ],
            'agamaOptions' => [
                'Islam' => 'Islam',
                'Kristen' => 'Kristen',
                'Katolik' => 'Katolik',
                'Hindu' => 'Hindu',
                'Buddha' => 'Buddha',
                'Konghucu' => 'Konghucu',
            ],
            'statusOptions' => [
                'menunggu' => 'Menunggu Jadwal Wawancara',
                'wawancara' => 'Wawancara',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak',
            ],
            'dokumen' => $this->santri ? $this->santri->dokumen : collect(),
        ]);
    }
} 