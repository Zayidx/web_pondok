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
    public $isRegistrationOpen = true;

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
        'tipe_pendaftaran' => '',
        'status_kesantrian' => 'aktif',
        'alamat' => '',
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



    public $pas_foto, $ijazah, $skhun, $akta_kelahiran, $kartu_keluarga;

    protected $rules = [
        'santriForm.nama_lengkap' => 'required|string|max:255|regex:/^[A-Za-z0-9\s\'\-]+$/',
        'santriForm.nisn' => 'required|digits:10|unique:psb_pendaftaran_santri,nisn',
        'santriForm.tempat_lahir' => 'required|string|max:255',
        'santriForm.tanggal_lahir' => 'required|date',
        'santriForm.jenis_kelamin' => 'required|in:L,P',
        'santriForm.agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
        'santriForm.email' => 'required|email|max:255|unique:psb_pendaftaran_santri,email',
        'santriForm.no_whatsapp' => 'required|regex:/^62[0-9]{9,12}$/',
        'santriForm.asal_sekolah' => 'required|string|max:255',
        'santriForm.tahun_lulus' => 'required|in:2024,2025',
        'santriForm.tipe_pendaftaran' => 'required|in:reguler,olimpiade,internasional',
        'santriForm.status_kesantrian' => 'required|in:aktif,nonaktif',
        'waliForm.nama_ayah' => 'required|string|max:255',
        'waliForm.pekerjaan_ayah' => 'required|string|max:255',
        'waliForm.pendidikan_ayah' => 'required|in:SD,SMP,SMA,D3,S1,S2,S3',
        'waliForm.penghasilan_ayah' => 'required|in:< 2 juta,2-5 juta,5-10 juta,> 10 juta',
        'waliForm.nama_ibu' => 'required|string|max:255',
        'waliForm.pekerjaan_ibu' => 'required|string|max:255',
        'waliForm.pendidikan_ibu' => 'required|in:SD,SMP,SMA,D3,S1,S2,S3',
        'waliForm.no_telp_ibu' => 'required|regex:/^[0-9]{10,13}$/',
        'santriForm.alamat' => 'required|string|max:500',
        'pas_foto' => 'required|file|mimes:jpg,png|max:2048',
        'ijazah' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'skhun' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'akta_kelahiran' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'kartu_keluarga' => 'required|file|mimes:pdf,jpg,png|max:2048',
        'terms' => 'accepted',
    ];

    protected $messages = [
        // Validasi untuk santriForm
        'santriForm.nama_lengkap.required' => 'Nama lengkap harus diisi.',
        'santriForm.nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf, angka, spasi, tanda petik (’), atau tanda hubung (-).',
        'santriForm.nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',
        'santriForm.nisn.required' => 'NISN harus diisi.',
        'santriForm.nisn.digits' => 'NISN harus terdiri dari tepat 10 digit angka.',
        'santriForm.nisn.unique' => 'NISN sudah terdaftar. Silakan gunakan NISN lain.',
        'santriForm.tempat_lahir.required' => 'Tempat lahir harus diisi.',
        'santriForm.tempat_lahir.max' => 'Tempat lahir tidak boleh lebih dari 255 karakter.',
        'santriForm.tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
        'santriForm.tanggal_lahir.date' => 'Tanggal lahir harus dalam format tanggal yang valid.',
        'santriForm.jenis_kelamin.required' => 'Jenis kelamin harus dipilih (Laki-laki atau Perempuan).',
        'santriForm.jenis_kelamin.in' => 'Jenis kelamin hanya boleh Laki-laki (L) atau Perempuan (P).',
        'santriForm.agama.required' => 'Agama harus dipilih.',
        'santriForm.agama.in' => 'Agama harus salah satu dari: Islam, Kristen, Katolik, Hindu, Buddha, atau Konghucu.',
        'santriForm.email.required' => 'Email harus diisi.',
        'santriForm.email.email' => 'Email harus dalam format yang valid (contoh: nama@domain.com).',
        'santriForm.email.max' => 'Email tidak boleh lebih dari 255 karakter.',
        'santriForm.email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
        'santriForm.no_whatsapp.required' => 'Nomor WhatsApp harus diisi.',
        'santriForm.no_whatsapp.regex' => 'Nomor WhatsApp harus diawali dengan 62 dan terdiri dari 11-14 digit angka tanpa spasi atau tanda baca.',
        'santriForm.asal_sekolah.required' => 'Asal sekolah harus diisi.',
        'santriForm.asal_sekolah.max' => 'Asal sekolah tidak boleh lebih dari 255 karakter.',
        'santriForm.tahun_lulus.required' => 'Tahun lulus harus dipilih.',
        'santriForm.tahun_lulus.in' => 'Tahun lulus hanya boleh 2024 atau 2025.',
        'santriForm.tipe_pendaftaran.required' => 'Tipe pendaftaran harus dipilih.',
        'santriForm.tipe_pendaftaran.in' => 'Tipe pendaftaran harus salah satu dari: Reguler, Olimpiade, atau Internasional.',
        'santriForm.status_kesantrian.required' => 'Status kesantrian harus dipilih.',
        'santriForm.status_kesantrian.in' => 'Status kesantrian hanya boleh Aktif atau Nonaktif.',

        // Validasi untuk waliForm
        'waliForm.nama_ayah.required' => 'Nama ayah harus diisi.',
        'waliForm.nama_ayah.max' => 'Nama ayah tidak boleh lebih dari 255 karakter.',
        'waliForm.pekerjaan_ayah.required' => 'Pekerjaan ayah harus diisi.',
        'waliForm.pekerjaan_ayah.max' => 'Pekerjaan ayah tidak boleh lebih dari 255 karakter.',
        'waliForm.pendidikan_ayah.required' => 'Pendidikan ayah harus dipilih.',
        'waliForm.pendidikan_ayah.in' => 'Pendidikan ayah harus salah satu dari: SD, SMP, SMA, D3, S1, S2, atau S3.',
        'waliForm.penghasilan_ayah.required' => 'Penghasilan ayah harus dipilih.',
        'waliForm.penghasilan_ayah.in' => 'Penghasilan ayah harus salah satu dari: < 2 juta, 2-5 juta, 5-10 juta, atau > 10 juta.',
        'waliForm.nama_ibu.required' => 'Nama ibu harus diisi.',
        'waliForm.nama_ibu.max' => 'Nama ibu tidak boleh lebih dari 255 karakter.',
        'waliForm.pekerjaan_ibu.required' => 'Pekerjaan ibu harus diisi.',
        'waliForm.pekerjaan_ibu.max' => 'Pekerjaan ibu tidak boleh lebih dari 255 karakter.',
        'waliForm.pendidikan_ibu.required' => 'Pendidikan ibu harus dipilih.',
        'waliForm.pendidikan_ibu.in' => 'Pendidikan ibu harus salah satu dari: SD, SMP, SMA, D3, S1, S2, atau S3.',
        'waliForm.no_telp_ibu.required' => 'Nomor telepon ibu harus diisi.',
        'waliForm.no_telp_ibu.regex' => 'Nomor telepon ibu harus terdiri dari 10 hingga 13 digit angka tanpa spasi atau tanda baca.',

        // Validasi untuk santriForm
        'santriForm.alamat.required' => 'Alamat lengkap harus diisi.',
        'santriForm.alamat.max' => 'Alamat lengkap tidak boleh lebih dari 500 karakter.',

        // Validasi untuk upload file
        'pas_foto.required' => 'Pas foto harus diunggah.',
        'pas_foto.mimes' => 'Pas foto harus berupa file JPG atau PNG.',
        'pas_foto.max' => 'Ukuran pas foto tidak boleh lebih dari 2MB.',
        'ijazah.required' => 'Ijazah harus diunggah.',
        'ijazah.mimes' => 'Ijazah harus berupa file PDF, JPG, atau PNG.',
        'ijazah.max' => 'Ukuran ijazah tidak boleh lebih dari 2MB.',
        'skhun.required' => 'SKHUN harus diunggah.',
        'skhun.mimes' => 'SKHUN harus berupa file PDF, JPG, atau PNG.',
        'skhun.max' => 'Ukuran SKHUN tidak boleh lebih dari 2MB.',
        'akta_kelahiran.required' => 'Akta kelahiran harus diunggah.',
        'akta_kelahiran.mimes' => 'Akta kelahiran harus berupa file PDF, JPG, atau PNG.',
        'akta_kelahiran.max' => 'Ukuran akta kelahiran tidak boleh lebih dari 2MB.',
        'kartu_keluarga.required' => 'Kartu keluarga harus diunggah.',
        'kartu_keluarga.mimes' => 'Kartu keluarga harus berupa file PDF, JPG, atau PNG.',
        'kartu_keluarga.max' => 'Ukuran kartu keluarga tidak boleh lebih dari 2MB.',

        // Validasi untuk syarat dan ketentuan
        'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
    ];

    public function mount()
    {
        $periode = \App\Models\PSB\Periode::where('status_periode', 'active')->first();
        if (!$periode) {
            $this->isRegistrationOpen = false;
        }
    }

    public function nextForm()
    {
        if (!$this->isRegistrationOpen) {
            return;
        }
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
            str_starts_with($key, 'waliForm.') && $this->formPage == 2
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
        if (!$this->isRegistrationOpen) {
            $this->addError('submit', 'Pendaftaran ditutup.');
            return;
        }
    
        $this->validate();
    
        DB::beginTransaction();
        try {
            $periode = \App\Models\PSB\Periode::where('status_periode', 'active')->first();
            if (!$periode) {
                throw new \Exception('Pendaftaran ditutup. Tidak ada periode aktif saat ini.');
            }
    
            $santri = \App\Models\PSB\PendaftaranSantri::create([
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
                'tipe_pendaftaran' => $this->santriForm['tipe_pendaftaran'],
                'status_santri' => 'menunggu',
                'alamat' => $this->santriForm['alamat'],
                'periode_id' => $periode->id,
            ]);
    
            \App\Models\PSB\WaliSantri::create([
                'pendaftaran_santri_id' => $santri->id,
                'nama_wali' => $this->waliForm['nama_ayah'] ?: $this->waliForm['nama_ibu'],
                'hubungan' => $this->waliForm['nama_ayah'] ? 'ayah' : 'ibu',
                'pekerjaan' => $this->waliForm['pekerjaan_ayah'] ?: $this->waliForm['pekerjaan_ibu'],
                'no_hp' => $this->waliForm['no_telp_ibu'],
                'alamat' => $this->santriForm['alamat'],
                'nama_ayah' => $this->waliForm['nama_ayah'],
                'pekerjaan_ayah' => $this->waliForm['pekerjaan_ayah'],
                'pendidikan_ayah' => $this->waliForm['pendidikan_ayah'],
                'penghasilan_ayah' => $this->waliForm['penghasilan_ayah'],
                'nama_ibu' => $this->waliForm['nama_ibu'],
                'pekerjaan_ibu' => $this->waliForm['pekerjaan_ibu'],
                'pendidikan_ibu' => $this->waliForm['pendidikan_ibu'],
                'no_telp_ibu' => $this->waliForm['no_telp_ibu'],
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
                        'nama_berkas' => $doc['file']->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $doc['file']->getClientOriginalExtension(),
                        'file_size' => $doc['file']->getSize(),
                        'is_verified' => false,
                        'keterangan' => 'Dokumen pendaftaran santri'
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
            \Log::error('Submit failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('submit', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showSuccessModal = false;
    }

    public function resetForm()
    {
        $this->santriForm = array_merge(array_fill_keys(array_keys($this->santriForm), ''), ['status_kesantrian' => 'aktif']);
        $this->waliForm = array_fill_keys(array_keys($this->waliForm), '');
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