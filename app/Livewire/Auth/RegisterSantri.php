<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class RegisterSantri extends Component
{
    use WithFileUploads;

    public $formPage = 1;
    public $successMessage = '';
    public $showSuccessModal = false;
    public $registrationNumber = '';
    public $terms = false;

    public $santriForm = [
        'nama_lengkap' => '',
        'nisn' => '',
        'tempat_lahir' => '',
        'tanggal_lahir' => '',
        'jenis_kelamin' => '',
        'agama' => '',
        'email' => '',
        'no_whatsapp' => '',
        'asal_sekolah' => '',
        'tahun_lulus' => '',
        'status_santri' => '',
        'status_kesantrian' => 'aktif',
    ];

    public $waliForm = [
        'nama_ayah' => '',
        'pekerjaan_ayah' => '',
        'pendidikan_ayah' => '',
        'penghasilan_ayah' => '',
        'nama_ibu' => '',
        'pekerjaan_ibu' => '',
        'pendidikan_ibu' => '',
        'no_telp_ibu' => '',
    ];

    public $alamatForm = [
        'alamat' => '',
    ];

    public $pas_foto, $ijazah, $skhun, $akta_kelahiran, $kartu_keluarga;

    protected $rules = [
        'santriForm.nama_lengkap' => 'required|string|max:255|regex:/^[A-Za-z0-9\s\'\-]+$/',
        'santriForm.nisn' => 'required|digits:10',
        'santriForm.tempat_lahir' => 'required|string|max:255',
        'santriForm.tanggal_lahir' => 'required|date',
        'santriForm.jenis_kelamin' => 'required|in:L,P',
        'santriForm.agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
        'santriForm.email' => 'required|email|max:255',
        'santriForm.no_whatsapp' => 'required|regex:/^[0-9]{10,13}$/',
        'santriForm.asal_sekolah' => 'required|string|max:255',
        'santriForm.tahun_lulus' => 'required|in:2024,2025',
        'santriForm.status_santri' => 'required|in:reguler,olimpiade,internasional',
        'santriForm.status_kesantrian' => 'required|in:aktif,nonaktif',

        'waliForm.nama_ayah' => 'required|string|max:255',
        'waliForm.pekerjaan_ayah' => 'required|string|max:255',
        'waliForm.pendidikan_ayah' => 'required|in:SD,SMP,SMA,D3,S1,S2,S3',
        'waliForm.penghasilan_ayah' => 'required|in:< 2 juta,2-5 juta,5-10 juta,> 10 juta',
        'waliForm.nama_ibu' => 'required|string|max:255',
        'waliForm.pekerjaan_ibu' => 'required|string|max:255',
        'waliForm.pendidikan_ibu' => 'required|in:SD,SMP,SMA,D3,S1,S2,S3',
        'waliForm.no_telp_ibu' => 'required|regex:/^[0-9]{10,13}$/',

        'alamatForm.alamat' => 'required|string|max:500',

        'pas_foto' => 'required|file|mimes:jpg,png|max:2048',
        'ijazah' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'skhun' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'akta_kelahiran' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'kartu_keluarga' => 'required|file|mimes:pdf,jpg,png|max:2048',

        'terms' => 'accepted',
    ];

    protected $messages = [
        'santriForm.nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'santriForm.nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf, angka, spasi, tanda petik, atau tanda hubung.',
        'santriForm.nisn.required' => 'NISN wajib diisi.',
        'santriForm.nisn.digits' => 'NISN harus terdiri dari 10 digit angka.',
        'santriForm.tempat_lahir.required' => 'Tempat lahir wajib diisi.',
        'santriForm.tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
        'santriForm.jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        'santriForm.agama.required' => 'Agama wajib dipilih.',
        'santriForm.email.required' => 'Email wajib diisi.',
        'santriForm.email.email' => 'Email harus dalam format yang valid.',
        'santriForm.no_whatsapp.required' => 'No. WhatsApp wajib diisi.',
        'santriForm.no_whatsapp.regex' => 'No. WhatsApp harus terdiri dari 10-13 digit angka.',
        'santriForm.asal_sekolah.required' => 'Asal sekolah wajib diisi.',
        'santriForm.tahun_lulus.required' => 'Tahun lulus wajib dipilih.',
        'santriForm.status_santri.required' => 'Program pilihan wajib dipilih.',
        'santriForm.status_kesantrian.required' => 'Status kesantrian wajib dipilih.',

        'waliForm.nama_ayah.required' => 'Nama ayah wajib diisi.',
        'waliForm.pekerjaan_ayah.required' => 'Pekerjaan ayah wajib diisi.',
        'waliForm.pendidikan_ayah.required' => 'Pendidikan ayah wajib dipilih.',
        'waliForm.penghasilan_ayah.required' => 'Penghasilan ayah wajib dipilih.',
        'waliForm.nama_ibu.required' => 'Nama ibu wajib diisi.',
        'waliForm.pekerjaan_ibu.required' => 'Pekerjaan ibu wajib diisi.',
        'waliForm.pendidikan_ibu.required' => 'Pendidikan ibu wajib dipilih.',
        'waliForm.no_telp_ibu.required' => 'No. HP orang tua wajib diisi.',
        'waliForm.no_telp_ibu.regex' => 'No. HP harus terdiri dari 10-13 digit angka.',

        'alamatForm.alamat.required' => 'Alamat lengkap wajib diisi.',

        'pas_foto.required' => 'Pas foto wajib diunggah.',
        'pas_foto.mimes' => 'Pas foto harus berupa file JPG atau PNG.',
        'pas_foto.max' => 'Ukuran pas foto tidak boleh melebihi 2MB.',
        'ijazah.required' => 'Ijazah wajib diunggah.',
        'ijazah.mimes' => 'Ijazah harus berupa file PDF, JPG, atau PNG.',
        'ijazah.max' => 'Ukuran ijazah tidak boleh melebihi 2MB.',
        'skhun.required' => 'SKHUN wajib diunggah.',
        'skhun.mimes' => 'SKHUN harus berupa file PDF, JPG, atau PNG.',
        'skhun.max' => 'Ukuran SKHUN tidak boleh melebihi 2MB.',
        'akta_kelahiran.required' => 'Akta kelahiran wajib diunggah.',
        'akta_kelahiran.mimes' => 'Akta kelahiran harus berupa file PDF, JPG, atau PNG.',
        'akta_kelahiran.max' => 'Ukuran akta kelahiran tidak boleh melebihi 2MB.',
        'kartu_keluarga.required' => 'Kartu keluarga wajib diunggah.',
        'kartu_keluarga.mimes' => 'Kartu keluarga harus berupa file PDF, JPG, atau PNG.',
        'kartu_keluarga.max' => 'Ukuran kartu keluarga tidak boleh melebihi 2MB.',

        'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
    ];

    public function nextForm()
    {
        $this->validateStep();
        if ($this->formPage < 3) {
            $this->formPage++;
        }
    }

    public function prevForm()
    {
        if ($this->formPage > 1) {
            $this->formPage--;
        }
    }

    protected function validateStep()
    {
        $stepRules = array_filter($this->rules, fn($key) => (
            str_starts_with($key, 'santriForm.') && $this->formPage == 1 ||
            str_starts_with($key, 'waliForm.') && $this->formPage == 2 ||
            str_starts_with($key, 'alamatForm.') && $this->formPage == 1
        ), ARRAY_FILTER_USE_KEY);

        if ($this->formPage == 3) {
            $stepRules = array_merge($stepRules, [
                'pas_foto' => $this->rules['pas_foto'],
                'ijazah' => $this->rules['ijazah'],
                'skhun' => $this->rules['skhun'],
                'akta_kelahiran' => $this->rules['akta_kelahiran'],
                'kartu_keluarga' => $this->rules['kartu_keluarga'],
                'terms' => $this->rules['terms'],
            ]);
        }

        $this->validate($stepRules, $this->messages);
    }

    public function submit()
    {
        $this->validate($this->rules, $this->messages);

        DB::beginTransaction();
        try {
            $periode = \App\Models\PSB\Periode::where('status_periode', 'active')->first();
            if (!$periode) {
                throw new \Exception('Pendaftaran ditutup. Tidak ada periode aktif saat ini.');
            }

            $santri = \App\Models\PSB\PendaftaranSantri::create([
                'nama_jenjang' => 'SMA',
                'nama_lengkap' => $this->santriForm['nama_lengkap'],
                'nisn' => $this->santriForm['nisn'],
                'tempat_lahir' => $this->santriForm['tempat_lahir'],
                'tanggal_lahir' => $this->santriForm['tanggal_lahir'],
                'jenis_kelamin' => $this->santriForm['jenis_kelamin'],
                'agama' => $this->santriForm['agama'],
                'email' => $this->santriForm['email'],
                'no_whatsapp' => $this->santriForm['no_whatsapp'],
                'asal_sekolah' => $this->santriForm['asal_sekolah'],
                'tahun_lulus' => $this->santriForm['tahun_lulus'],
                'status_santri' => $this->santriForm['status_santri'],
                'status_kesantrian' => $this->santriForm['status_kesantrian'],
                'periode_id' => $periode->id,
            ]);

            \App\Models\PSB\WaliSantri::create([
                'pendaftaran_santri_id' => $santri->id,
                'nama_ayah' => $this->waliForm['nama_ayah'],
                'pekerjaan_ayah' => $this->waliForm['pekerjaan_ayah'],
                'pendidikan_ayah' => $this->waliForm['pendidikan_ayah'],
                'penghasilan_ayah' => $this->waliForm['penghasilan_ayah'],
                'nama_ibu' => $this->waliForm['nama_ibu'],
                'pekerjaan_ibu' => $this->waliForm['pekerjaan_ibu'],
                'pendidikan_ibu' => $this->waliForm['pendidikan_ibu'],
                'no_telp_ibu' => $this->waliForm['no_telp_ibu'],
                'alamat' => $this->alamatForm['alamat'],
            ]);

            $documents = [
                ['file' => $this->pas_foto, 'jenis' => 'Pas Foto', 'path' => 'images/santri'],
                ['file' => $this->ijazah, 'jenis' => 'Ijazah', 'path' => 'documents'],
                ['file' => $this->skhun, 'jenis' => 'SKHUN', 'path' => 'documents'],
                ['file' => $this->akta_kelahiran, 'jenis' => 'Akta Kelahiran', 'path' => 'documents'],
                ['file' => $this->kartu_keluarga, 'jenis' => 'Kartu Keluarga', 'path' => 'documents'],
            ];

            foreach ($documents as $doc) {
                if ($doc['file']) {
                    $path = $doc['file']->store($doc['path'], 'public');
                    \App\Models\PSB\Dokumen::create([
                        'santri_id' => $santri->id,
                        'jenis_berkas' => $doc['jenis'],
                        'file_path' => $path,
                        'tanggal' => now(),
                    ]);
                }
            }

            DB::commit();
            $this->registrationNumber = 'PPDB2025' . str_pad($santri->id, 3, '0', STR_PAD_LEFT);
            $this->successMessage = 'Pendaftaran santri berhasil disimpan!';
            $this->showSuccessModal = true;
            $this->resetForm();
            $this->formPage = 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Submit failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('submit', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showSuccessModal = false;
    }

    public function resetForm()
    {
        $this->santriForm = array_fill_keys(array_keys($this->santriForm), '');
        $this->waliForm = array_fill_keys(array_keys($this->waliForm), '');
        $this->alamatForm = array_fill_keys(array_keys($this->alamatForm), '');
        $this->pas_foto = null;
        $this->ijazah = null;
        $this->skhun = null;
        $this->akta_kelahiran = null;
        $this->kartu_keluarga = null;
        $this->terms = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.auth.register-santri')->layout('components.layouts.auth-ppdb');
    }
}