<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

class EditRegistration extends Component
{
    use WithFileUploads;

    #[Layout('components.layouts.app')]
    #[Title('Edit Data Pendaftaran Santri')]

    public $santriId;
    public $santri;
    public $formPage = 1;
    public $foto;
    public $fotoSantri;
    public $dokumenBaru = [
        'ijazah' => null,
        'skhun' => null,
        'kk' => null,
        'akta' => null,
    ];
    public $editForm = [];
    public $agamaOptions;

    protected function rules()
    {
        return [
            'editForm.nama_lengkap' => 'required|string|max:255',
            'editForm.nisn' => 'required|string|max:20',
            'editForm.tempat_lahir' => 'required|string|max:100',
            'editForm.tanggal_lahir' => 'required|date',
            'editForm.jenis_kelamin' => 'required|in:L,P',
            'editForm.agama' => 'required|string|max:50',
            'editForm.email' => 'required|email|max:255',
            'editForm.no_whatsapp' => 'required|string|max:15',
            'editForm.asal_sekolah' => 'required|string|max:255',
            'editForm.tahun_lulus' => 'required|digits:4',

            'editForm.nama_ayah' => 'required|string|max:255',
            'editForm.pekerjaan_ayah' => 'required|string|max:100',
            'editForm.pendidikan_ayah' => 'required|string|max:50',
            'editForm.penghasilan_ayah' => 'required|string|max:50',
            'editForm.nama_ibu' => 'required|string|max:255',
            'editForm.pekerjaan_ibu' => 'required|string|max:100',
            'editForm.pendidikan_ibu' => 'required|string|max:50',
            'editForm.no_telp_ibu' => 'required|string|max:15',
            'editForm.alamat' => 'required|string|max:255',

            'foto' => 'nullable|image|max:2048',
            'dokumenBaru.ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumenBaru.skhun' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumenBaru.kk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumenBaru.akta' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    public function mount($santriId)
    {
        $this->santriId = $santriId;
        $this->loadSantriData();
        $this->agamaOptions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
    }

    public function loadSantriData()
    {
        try {
            $santri = PendaftaranSantri::with(['wali', 'dokumen'])->findOrFail($this->santriId);
            $this->santri = $santri;
            $this->fotoSantri = $santri->dokumen->where('jenis_berkas', 'Pas Foto')->first()?->file_path;

            $this->editForm = [
                'nama_lengkap' => $santri->nama_lengkap,
                'nisn' => $santri->nisn,
                'tempat_lahir' => $santri->tempat_lahir,
                'tanggal_lahir' => $santri->tanggal_lahir ? $santri->tanggal_lahir->format('Y-m-d') : null,
                'jenis_kelamin' => $santri->jenis_kelamin,
                'agama' => $santri->agama,
                'email' => $santri->email,
                'no_whatsapp' => $santri->no_whatsapp,
                'asal_sekolah' => $santri->asal_sekolah,
                'tahun_lulus' => $santri->tahun_lulus,
                'nama_ayah' => $santri->wali->nama_ayah ?? '',
                'pekerjaan_ayah' => $santri->wali->pekerjaan_ayah ?? '',
                'pendidikan_ayah' => $santri->wali->pendidikan_ayah ?? '',
                'penghasilan_ayah' => $santri->wali->penghasilan_ayah ?? '',
                'nama_ibu' => $santri->wali->nama_ibu ?? '',
                'pekerjaan_ibu' => $santri->wali->pekerjaan_ibu ?? '',
                'pendidikan_ibu' => $santri->wali->pendidikan_ibu ?? '',
                'no_telp_ibu' => $santri->wali->no_telp_ibu ?? '',
                'alamat' => $santri->wali->alamat ?? '',
            ];
        } catch (\Exception $e) {
            Log::error('Gagal memuat data santri untuk diedit: ' . $e->getMessage());
            session()->flash('error', 'Data santri tidak dapat ditemukan.');
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

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $this->santri->update([
                'nama_lengkap' => $this->editForm['nama_lengkap'],
                'nisn' => $this->editForm['nisn'],
                'tempat_lahir' => $this->editForm['tempat_lahir'],
                'tanggal_lahir' => $this->editForm['tanggal_lahir'],
                'jenis_kelamin' => $this->editForm['jenis_kelamin'],
                'agama' => $this->editForm['agama'],
                'email' => $this->editForm['email'],
                'no_whatsapp' => $this->editForm['no_whatsapp'],
                'asal_sekolah' => $this->editForm['asal_sekolah'],
                'tahun_lulus' => $this->editForm['tahun_lulus'],
            ]);

            $this->santri->wali()->updateOrCreate(
                ['pendaftaran_santri_id' => $this->santri->id],
                [
                    'nama_ayah' => $this->editForm['nama_ayah'],
                    'pekerjaan_ayah' => $this->editForm['pekerjaan_ayah'],
                    'pendidikan_ayah' => $this->editForm['pendidikan_ayah'],
                    'penghasilan_ayah' => $this->editForm['penghasilan_ayah'],
                    'nama_ibu' => $this->editForm['nama_ibu'],
                    'pekerjaan_ibu' => $this->editForm['pekerjaan_ibu'],
                    'pendidikan_ibu' => $this->editForm['pendidikan_ibu'],
                    'no_telp_ibu' => $this->editForm['no_telp_ibu'],
                    'alamat' => $this->editForm['alamat'],
                ]
            );

            if ($this->foto) {
                if ($this->fotoSantri) {
                    Storage::disk('public')->delete($this->fotoSantri);
                }
                $fotoPath = $this->foto->store('santri/foto', 'public');
                $this->santri->dokumen()->updateOrCreate(
                    ['jenis_berkas' => 'Pas Foto'],
                    ['file_path' => $fotoPath, 'tanggal' => now()]
                );
            }

            $documentTypes = [
                'ijazah' => 'Ijazah',
                'skhun' => 'SKHUN',
                'kk' => 'Kartu Keluarga',
                'akta' => 'Akta Kelahiran',
            ];

            foreach ($this->dokumenBaru as $type => $file) {
                if ($file && isset($documentTypes[$type])) {
                    $oldDoc = $this->santri->dokumen()->where('jenis_berkas', $documentTypes[$type])->first();
                    if ($oldDoc && $oldDoc->file_path) {
                        Storage::disk('public')->delete($oldDoc->file_path);
                    }
                    $path = $file->store('santri/dokumen', 'public');
                    $this->santri->dokumen()->updateOrCreate(
                        ['jenis_berkas' => $documentTypes[$type]],
                        ['file_path' => $path, 'tanggal' => now()]
                    );
                }
            }

            DB::commit();
            session()->flash('success', 'Data santri berhasil diperbarui.');
            return redirect()->route('admin.master-psb.detail-registration', ['santriId' => $this->santriId]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui data santri: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.admin.psb.edit-registration', [
            'dokumen' => $this->santri ? $this->santri->dokumen : collect(),
        ]);
    }
}