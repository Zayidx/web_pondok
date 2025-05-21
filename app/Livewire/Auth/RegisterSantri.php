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

    // Santri Form
    public $santriForm = [
        'nama_lengkap' => '',
        'nisn' => '',
        'nism' => '',
        'npsn' => '',
        'kewarganegaraan' => '',
        'nik' => '',
        'riwayat_penyakit' => '',
        'tempat_lahir' => '',
        'tanggal_lahir' => '',
        'jenis_kelamin' => '',
        'jumlah_saudara_kandung' => '',
        'anak_keberapa' => '',
        'hobi' => '',
        'aktivitas_pendidikan' => '',
        'no_kip' => '',
        'no_kk' => '',
        'status_santri' => '',
        'kelas' => '',
        'nama_kepala_keluarga' => '',
        'no_hp_kepala_keluarga' => '',
        'asal_sekolah' => '',
        'pembiayaan' => '',
        'no_whatsapp' => '',
        'email' => '',
        'status_kesantrian' => 'aktif',
    ];

    // Wali Form
    public $waliForm = [
        'nama_ayah' => '',
        'status_ayah' => '',
        'kewarganegaraan_ayah' => '',
        'nik_ayah' => '',
        'tempat_lahir_ayah' => '',
        'tanggal_lahir_ayah' => '',
        'pendidikan_terakhir_ayah' => '',
        'pekerjaan_ayah' => '',
        'penghasilan_ayah' => '',
        'no_telp_ayah' => '',
        'nama_ibu' => '',
        'status_ibu' => '',
        'kewarganegaraan_ibu' => '',
        'nik_ibu' => '',
        'tempat_lahir_ibu' => '',
        'tanggal_lahir_ibu' => '',
        'pendidikan_terakhir_ibu' => '',
        'pekerjaan_ibu' => '',
        'penghasilan_ibu' => '',
        'no_telp_ibu' => '',
        'status_orang_tua' => '',
    ];

    // Alamat Form
    public $alamatForm = [
        'status_kepemilikan_rumah' => '',
        'provinsi' => '',
        'kabupaten' => '',
        'kecamatan' => '',
        'kelurahan' => '',
        'rt' => '',
        'rw' => '',
        'kode_pos' => '',
        'alamat' => '',
    ];

    // File Uploads
    public $foto, $ijazah, $kartu_keluarga, $bukti_pembayaran;

    protected $rules = [
        'santriForm.nama_lengkap' => 'required|string|max:255|regex:/^[A-Za-z\s\'\-]+$/',
        'santriForm.nisn' => 'nullable|digits:10',
        'santriForm.nism' => 'nullable|min:8',
        'santriForm.npsn' => 'nullable|digits:8',
        'santriForm.kewarganegaraan' => 'nullable|in:wni,wna',
        'santriForm.nik' => 'nullable|digits:16',
        'santriForm.riwayat_penyakit' => 'nullable|string|max:255',
        'santriForm.tempat_lahir' => 'nullable|string|max:255',
        'santriForm.tanggal_lahir' => 'nullable|date',
        'santriForm.jenis_kelamin' => 'required|in:putera,puteri',
        'santriForm.jumlah_saudara_kandung' => 'nullable|integer|min:0',
        'santriForm.anak_keberapa' => 'nullable|integer|min:1',
        'santriForm.hobi' => 'nullable|string|max:255',
        'santriForm.aktivitas_pendidikan' => 'nullable|in:aktif,nonaktif',
        'santriForm.no_kip' => 'nullable|min:13',
        'santriForm.no_kk' => 'nullable|digits:16',
        'santriForm.status_santri' => 'nullable|in:reguler,dhuafa,yatim_piatu',
        'santriForm.kelas' => 'nullable|in:SMP,SMA',
        'santriForm.nama_kepala_keluarga' => 'nullable|string|max:255',
        'santriForm.no_hp_kepala_keluarga' => 'nullable|regex:/^[0-9]{10,13}$/',
        'santriForm.asal_sekolah' => 'nullable|string|max:255',
        'santriForm.pembiayaan' => 'nullable|in:Orang Tua (Ayah/Ibu),Beasiswa,Wali(Kakak/Paman/Bibi)',
        'santriForm.no_whatsapp' => 'nullable|regex:/^[0-9]{10,13}$/',
        'santriForm.email' => 'nullable|email|max:255',
        'santriForm.status_kesantrian' => 'required|in:aktif,nonaktif',

        'waliForm.nama_ayah' => 'nullable|string|max:255',
        'waliForm.status_ayah' => 'nullable|in:hidup,meninggal',
        'waliForm.kewarganegaraan_ayah' => 'nullable|in:wni,wna',
        'waliForm.nik_ayah' => 'nullable|digits:16',
        'waliForm.tempat_lahir_ayah' => 'nullable|string|max:255',
        'waliForm.tanggal_lahir_ayah' => 'nullable|date',
        'waliForm.pendidikan_terakhir_ayah' => 'nullable|in:tidak sekolah,sd,smp,sma,slta,diploma,sarjana',
        'waliForm.pekerjaan_ayah' => 'nullable|string|max:255',
        'waliForm.penghasilan_ayah' => 'nullable|numeric|min:0',
        'waliForm.no_telp_ayah' => 'nullable|regex:/^[0-9]{10,13}$/',
        'waliForm.nama_ibu' => 'nullable|string|max:255',
        'waliForm.status_ibu' => 'nullable|in:hidup,meninggal',
        'waliForm.kewarganegaraan_ibu' => 'nullable|in:wni,wna',
        'waliForm.nik_ibu' => 'nullable|digits:16',
        'waliForm.tempat_lahir_ibu' => 'nullable|string|max:255',
        'waliForm.tanggal_lahir_ibu' => 'nullable|date',
        'waliForm.pendidikan_terakhir_ibu' => 'nullable|in:tidak sekolah,sd,smp,sma,slta,diploma,sarjana',
        'waliForm.pekerjaan_ibu' => 'nullable|string|max:255',
        'waliForm.penghasilan_ibu' => 'nullable|numeric|min:0',
        'waliForm.no_telp_ibu' => 'nullable|regex:/^[0-9]{10,13}$/',
        'waliForm.status_orang_tua' => 'nullable|in:kawin,cerai hidup,cerai mati',

        'alamatForm.status_kepemilikan_rumah' => 'nullable|string|max:255',
        'alamatForm.provinsi' => 'nullable|string|max:255',
        'alamatForm.kabupaten' => 'nullable|string|max:255',
        'alamatForm.kecamatan' => 'nullable|string|max:255',
        'alamatForm.kelurahan' => 'nullable|string|max:255',
        'alamatForm.rt' => 'nullable|string|max:10',
        'alamatForm.rw' => 'nullable|string|max:10',
        'alamatForm.kode_pos' => 'nullable|digits:5',
        'alamatForm.alamat' => 'required|string|max:500',

        'foto' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
        'ijazah' => 'nullable|file|mimes:pdf|max:2048',
        'kartu_keluarga' => 'nullable|file|mimes:pdf|max:2048',
        'bukti_pembayaran' => 'nullable|file|mimes:pdf|max:2048',
    ];

    protected $messages = [
        'santriForm.nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'santriForm.nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf, spasi, tanda petik, atau tanda hubung (contoh: Ahmad Syakir).',
        'santriForm.nisn.digits' => 'NISN harus terdiri dari 10 digit angka.',
        'santriForm.nism.min' => 'NISM minimal 8 karakter.',
        'santriForm.npsn.digits' => 'NPSN harus terdiri dari 8 digit angka.',
        'santriForm.nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
        'santriForm.tanggal_lahir.date' => 'Tanggal lahir harus dalam format tanggal yang valid.',
        'santriForm.jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
        'santriForm.jumlah_saudara_kandung.integer' => 'Jumlah saudara kandung harus berupa angka.',
        'santriForm.anak_keberapa.integer' => 'Anak keberapa harus berupa angka.',
        'santriForm.no_kip.min' => 'No KIP minimal 13 karakter.',
        'santriForm.no_kk.digits' => 'No KK harus terdiri dari 16 digit angka.',
        'santriForm.no_hp_kepala_keluarga.regex' => 'No telepon kepala keluarga harus terdiri dari 10-13 digit angka (contoh: 081234567890).',
        'santriForm.no_whatsapp.regex' => 'No WhatsApp harus terdiri dari 10-13 digit angka (contoh: 081234567890).',
        'santriForm.email.email' => 'Email harus dalam format yang valid (contoh: nama@domain.com).',
        'santriForm.status_kesantrian.required' => 'Status kesantrian wajib dipilih.',

        'waliForm.nik_ayah.digits' => 'NIK ayah harus terdiri dari 16 digit angka.',
        'waliForm.tanggal_lahir_ayah.date' => 'Tanggal lahir ayah harus dalam format tanggal yang valid.',
        'waliForm.penghasilan_ayah.numeric' => 'Penghasilan ayah harus berupa angka.',
        'waliForm.no_telp_ayah.regex' => 'No telepon ayah harus terdiri dari 10-13 digit angka (contoh: 081234567890).',
        'waliForm.nik_ibu.digits' => 'NIK ibu harus terdiri dari 16 digit angka.',
        'waliForm.tanggal_lahir_ibu.date' => 'Tanggal lahir ibu harus dalam format tanggal yang valid.',
        'waliForm.penghasilan_ibu.numeric' => 'Penghasilan ibu harus berupa angka.',
        'waliForm.no_telp_ibu.regex' => 'No telepon ibu harus terdiri dari 10-13 digit angka (contoh: 081234567890).',

        'alamatForm.kode_pos.digits' => 'Kode pos harus terdiri dari 5 digit angka.',
        'alamatForm.alamat.required' => 'Alamat lengkap wajib diisi.',

        'foto.mimes' => 'Foto harus berupa file PNG, JPG, atau JPEG.',
        'foto.max' => 'Ukuran file foto tidak boleh melebihi 2MB.',
        'ijazah.mimes' => 'Ijazah harus berupa file PDF.',
        'ijazah.max' => 'Ukuran file ijazah tidak boleh melebihi 2MB.',
        'kartu_keluarga.mimes' => 'Kartu keluarga harus berupa file PDF.',
        'kartu_keluarga.max' => 'Ukuran file kartu keluarga tidak boleh melebihi 2MB.',
        'bukti_pembayaran.mimes' => 'Bukti pembayaran harus berupa file PDF.',
        'bukti_pembayaran.max' => 'Ukuran file bukti pembayaran tidak boleh melebihi 2MB.',
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
        if ($this->formPage == 1) {
            $this->validate([
                'santriForm.nama_lengkap' => $this->rules['santriForm.nama_lengkap'],
                'santriForm.nisn' => $this->rules['santriForm.nisn'],
                'santriForm.nism' => $this->rules['santriForm.nism'],
                'santriForm.npsn' => $this->rules['santriForm.npsn'],
                'santriForm.kewarganegaraan' => $this->rules['santriForm.kewarganegaraan'],
                'santriForm.nik' => $this->rules['santriForm.nik'],
                'santriForm.riwayat_penyakit' => $this->rules['santriForm.riwayat_penyakit'],
                'santriForm.tempat_lahir' => $this->rules['santriForm.tempat_lahir'],
                'santriForm.tanggal_lahir' => $this->rules['santriForm.tanggal_lahir'],
                'santriForm.jenis_kelamin' => $this->rules['santriForm.jenis_kelamin'],
                'santriForm.jumlah_saudara_kandung' => $this->rules['santriForm.jumlah_saudara_kandung'],
                'santriForm.anak_keberapa' => $this->rules['santriForm.anak_keberapa'],
                'santriForm.hobi' => $this->rules['santriForm.hobi'],
                'santriForm.aktivitas_pendidikan' => $this->rules['santriForm.aktivitas_pendidikan'],
                'santriForm.no_kip' => $this->rules['santriForm.no_kip'],
                'santriForm.no_kk' => $this->rules['santriForm.no_kk'],
                'santriForm.status_santri' => $this->rules['santriForm.status_santri'],
                'santriForm.kelas' => $this->rules['santriForm.kelas'],
                'santriForm.nama_kepala_keluarga' => $this->rules['santriForm.nama_kepala_keluarga'],
                'santriForm.no_hp_kepala_keluarga' => $this->rules['santriForm.no_hp_kepala_keluarga'],
                'santriForm.asal_sekolah' => $this->rules['santriForm.asal_sekolah'],
                'santriForm.pembiayaan' => $this->rules['santriForm.pembiayaan'],
                'santriForm.no_whatsapp' => $this->rules['santriForm.no_whatsapp'],
                'santriForm.email' => $this->rules['santriForm.email'],
                'santriForm.status_kesantrian' => $this->rules['santriForm.status_kesantrian'],
                'foto' => $this->rules['foto'],
                'ijazah' => $this->rules['ijazah'],
                'kartu_keluarga' => $this->rules['kartu_keluarga'],
                'bukti_pembayaran' => $this->rules['bukti_pembayaran'],
            ], $this->messages);
        } elseif ($this->formPage == 2) {
            $this->validate([
                'waliForm.nama_ayah' => $this->rules['waliForm.nama_ayah'],
                'waliForm.status_ayah' => $this->rules['waliForm.status_ayah'],
                'waliForm.kewarganegaraan_ayah' => $this->rules['waliForm.kewarganegaraan_ayah'],
                'waliForm.nik_ayah' => $this->rules['waliForm.nik_ayah'],
                'waliForm.tempat_lahir_ayah' => $this->rules['waliForm.tempat_lahir_ayah'],
                'waliForm.tanggal_lahir_ayah' => $this->rules['waliForm.tanggal_lahir_ayah'],
                'waliForm.pendidikan_terakhir_ayah' => $this->rules['waliForm.pendidikan_terakhir_ayah'],
                'waliForm.pekerjaan_ayah' => $this->rules['waliForm.pekerjaan_ayah'],
                'waliForm.penghasilan_ayah' => $this->rules['waliForm.penghasilan_ayah'],
                'waliForm.no_telp_ayah' => $this->rules['waliForm.no_telp_ayah'],
                'waliForm.nama_ibu' => $this->rules['waliForm.nama_ibu'],
                'waliForm.status_ibu' => $this->rules['waliForm.status_ibu'],
                'waliForm.kewarganegaraan_ibu' => $this->rules['waliForm.kewarganegaraan_ibu'],
                'waliForm.nik_ibu' => $this->rules['waliForm.nik_ibu'],
                'waliForm.tempat_lahir_ibu' => $this->rules['waliForm.tempat_lahir_ibu'],
                'waliForm.tanggal_lahir_ibu' => $this->rules['waliForm.tanggal_lahir_ibu'],
                'waliForm.pendidikan_terakhir_ibu' => $this->rules['waliForm.pendidikan_terakhir_ibu'],
                'waliForm.pekerjaan_ibu' => $this->rules['waliForm.pekerjaan_ibu'],
                'waliForm.penghasilan_ibu' => $this->rules['waliForm.penghasilan_ibu'],
                'waliForm.no_telp_ibu' => $this->rules['waliForm.no_telp_ibu'],
                'waliForm.status_orang_tua' => $this->rules['waliForm.status_orang_tua'],
            ], $this->messages);
        } elseif ($this->formPage == 3) {
            $this->validate([
                'alamatForm.status_kepemilikan_rumah' => $this->rules['alamatForm.status_kepemilikan_rumah'],
                'alamatForm.provinsi' => $this->rules['alamatForm.provinsi'],
                'alamatForm.kabupaten' => $this->rules['alamatForm.kabupaten'],
                'alamatForm.kecamatan' => $this->rules['alamatForm.kecamatan'],
                'alamatForm.kelurahan' => $this->rules['alamatForm.kelurahan'],
                'alamatForm.rt' => $this->rules['alamatForm.rt'],
                'alamatForm.rw' => $this->rules['alamatForm.rw'],
                'alamatForm.kode_pos' => $this->rules['alamatForm.kode_pos'],
                'alamatForm.alamat' => $this->rules['alamatForm.alamat'],
            ], $this->messages);
        }
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
                'nama_jenjang' => $this->santriForm['kelas'] ?? 'SMP',
                'nama_lengkap' => $this->santriForm['nama_lengkap'],
                'nisn' => $this->santriForm['nisn'],
                'nism' => $this->santriForm['nism'],
                'npsn' => $this->santriForm['npsn'],
                'kip' => $this->santriForm['no_kip'],
                'no_kk' => $this->santriForm['no_kk'],
                'jumlah_saudara_kandung' => $this->santriForm['jumlah_saudara_kandung'],
                'anak_keberapa' => $this->santriForm['anak_keberapa'],
                'jenis_kelamin' => $this->santriForm['jenis_kelamin'],
                'tanggal_lahir' => $this->santriForm['tanggal_lahir'],
                'tempat_lahir' => $this->santriForm['tempat_lahir'],
                'asal_sekolah' => $this->santriForm['asal_sekolah'],
                'no_whatsapp' => $this->santriForm['no_whatsapp'],
                'email' => $this->santriForm['email'],
                'status_santri' => $this->santriForm['status_santri'],
                'kewarganegaraan' => $this->santriForm['kewarganegaraan'],
                'kelas' => $this->santriForm['kelas'],
                'pembiayaan' => $this->santriForm['pembiayaan'],
                'riwayat_penyakit' => $this->santriForm['riwayat_penyakit'],
                'hobi' => $this->santriForm['hobi'],
                'aktivitas_pendidikan' => $this->santriForm['aktivitas_pendidikan'],
                'nik' => $this->santriForm['nik'],
                'status_kesantrian' => $this->santriForm['status_kesantrian'],
                'periode_id' => $periode->id,
            ]);

            \App\Models\PSB\WaliSantri::create([
                'pendaftaran_santri_id' => $santri->id,
                'nama_kepala_keluarga' => $this->santriForm['nama_kepala_keluarga'],
                'no_hp_kepala_keluarga' => $this->santriForm['no_hp_kepala_keluarga'],
                'nama_ayah' => $this->waliForm['nama_ayah'],
                'status_ayah' => $this->waliForm['status_ayah'],
                'kewarganegaraan_ayah' => $this->waliForm['kewarganegaraan_ayah'],
                'nik_ayah' => $this->waliForm['nik_ayah'],
                'tempat_lahir_ayah' => $this->waliForm['tempat_lahir_ayah'],
                'tanggal_lahir_ayah' => $this->waliForm['tanggal_lahir_ayah'],
                'pendidikan_terakhir_ayah' => $this->waliForm['pendidikan_terakhir_ayah'],
                'pekerjaan_ayah' => $this->waliForm['pekerjaan_ayah'],
                'penghasilan_ayah' => $this->waliForm['penghasilan_ayah'],
                'no_telp_ayah' => $this->waliForm['no_telp_ayah'],
                'nama_ibu' => $this->waliForm['nama_ibu'],
                'status_ibu' => $this->waliForm['status_ibu'],
                'kewarganegaraan_ibu' => $this->waliForm['kewarganegaraan_ibu'],
                'nik_ibu' => $this->waliForm['nik_ibu'],
                'tempat_lahir_ibu' => $this->waliForm['tempat_lahir_ibu'],
                'tanggal_lahir_ibu' => $this->waliForm['tanggal_lahir_ibu'],
                'pendidikan_terakhir_ibu' => $this->waliForm['pendidikan_terakhir_ibu'],
                'pekerjaan_ibu' => $this->waliForm['pekerjaan_ibu'],
                'penghasilan_ibu' => $this->waliForm['penghasilan_ibu'],
                'no_telp_ibu' => $this->waliForm['no_telp_ibu'],
                'status_orang_tua' => $this->waliForm['status_orang_tua'],
                'provinsi' => $this->alamatForm['provinsi'],
                'kabupaten' => $this->alamatForm['kabupaten'],
                'kecamatan' => $this->alamatForm['kecamatan'],
                'kelurahan' => $this->alamatForm['kelurahan'],
                'rt' => $this->alamatForm['rt'],
                'rw' => $this->alamatForm['rw'],
                'kode_pos' => $this->alamatForm['kode_pos'],
                'status_kepemilikan_rumah' => $this->alamatForm['status_kepemilikan_rumah'],
                'alamat' => $this->alamatForm['alamat'],
            ]);

            $documents = [
                ['file' => $this->foto, 'jenis' => 'Foto', 'path' => 'images/santri'],
                ['file' => $this->ijazah, 'jenis' => 'Ijazah', 'path' => 'documents'],
                ['file' => $this->kartu_keluarga, 'jenis' => 'Kartu Keluarga', 'path' => 'documents'],
                ['file' => $this->bukti_pembayaran, 'jenis' => 'Bukti Pembayaran', 'path' => 'documents'],
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

            if ($this->bukti_pembayaran) {
                $path = $this->bukti_pembayaran->store('documents', 'public');
                \App\Models\PSB\Pembayaran::create([
                    'santri_id' => $santri->id,
                    'jumlah' => null,
                    'tanggal_bayar' => now(),
                    'bukti_transfer' => $path,
                    'status_pembayaran' => 'pending',
                ]);
            }

            DB::commit();
            $this->successMessage = 'Pendaftaran santri berhasil disimpan!';
            $this->resetForm();
            $this->formPage = 1;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Submit failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('submit', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->santriForm = array_fill_keys(array_keys($this->santriForm), '');
        $this->waliForm = array_fill_keys(array_keys($this->waliForm), '');
        $this->alamatForm = array_fill_keys(array_keys($this->alamatForm), '');
        $this->foto = null;
        $this->ijazah = null;
        $this->kartu_keluarga = null;
        $this->bukti_pembayaran = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.auth.register-santri')->layout('components.layouts.register-santri');
    }
}