<?php
// File: app/Livewire/Admin/PSB/SertifikatPenerimaan.php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PSB\SuratPenerimaanSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;

class SertifikatPenerimaan extends Component
{
    // Menggunakan trait ini untuk mengaktifkan fitur upload file di Livewire.
    use WithFileUploads;

    // Memberikan judul halaman browser.
    #[Title('Pengaturan Template Sertifikat')]

    // Properti publik akan tersedia di view.
    public $settings; // Menampung instance model SuratPenerimaanSetting.
    public $logo; // Menampung file logo yang sedang di-upload.
    public $stempel; // Menampung file stempel yang sedang di-upload.

    // Properti untuk binding dengan form input.
    public $nama_pesantren, $nama_yayasan, $alamat_pesantren, $telepon_pesantren, $email_pesantren;
    public $nama_direktur, $nip_direktur, $nama_kepala_admin, $nip_kepala_admin;
    public $catatan_penting; // Nama properti disamakan dengan nama kolom di database.

    // Aturan validasi untuk setiap field form.
    protected function rules()
    {
        return [
            'nama_pesantren' => 'required|string|max:255',
            'nama_yayasan' => 'required|string|max:255',
            'alamat_pesantren' => 'required|string',
            'telepon_pesantren' => 'required|string|max:20',
            'email_pesantren' => 'required|email|max:255',
            'logo' => 'nullable|image|max:1024', // Validasi untuk file logo: boleh kosong, harus gambar, maks 1MB.
            'stempel' => 'nullable|image|max:1024', // Validasi untuk file stempel.
            'nama_direktur' => 'required|string|max:255',
            'nip_direktur' => 'required|string|max:50',
            'nama_kepala_admin' => 'required|string|max:255',
            'nip_kepala_admin' => 'required|string|max:50',
            'catatan_penting' => 'nullable|string|max:1000',
        ];
    }

    // Metode 'mount' dijalankan saat komponen pertama kali di-load.
    public function mount()
    {
        // Mengambil data pengaturan pertama, jika tidak ada, buat instance model baru.
        $this->settings = SuratPenerimaanSetting::firstOrNew(['id' => 1]);
        // Memuat data dari model ke dalam properti form.
        $this->loadSettings();
    }

    // Fungsi untuk memuat data dari $settings ke properti publik komponen.
    public function loadSettings()
    {
        // Mengisi properti form dari data yang ada di database.
        $this->nama_pesantren = $this->settings->nama_pesantren;
        $this->nama_yayasan = $this->settings->nama_yayasan;
        $this->alamat_pesantren = $this->settings->alamat_pesantren;
        $this->telepon_pesantren = $this->settings->telepon_pesantren;
        $this->email_pesantren = $this->settings->email_pesantren;
        $this->nama_direktur = $this->settings->nama_direktur;
        $this->nip_direktur = $this->settings->nip_direktur;
        $this->nama_kepala_admin = $this->settings->nama_kepala_admin;
        $this->nip_kepala_admin = $this->settings->nip_kepala_admin;
        $this->catatan_penting = $this->settings->catatan_penting;
    }

    // Metode 'save' dijalankan saat form di-submit.
    public function save()
    {
        // Menjalankan validasi berdasarkan aturan di $rules.
        $this->validate();

        $dataToUpdate = [
            'nama_pesantren' => $this->nama_pesantren,
            'nama_yayasan' => $this->nama_yayasan,
            'alamat_pesantren' => $this->alamat_pesantren,
            'telepon_pesantren' => $this->telepon_pesantren,
            'email_pesantren' => $this->email_pesantren,
            'nama_direktur' => $this->nama_direktur,
            'nip_direktur' => $this->nip_direktur,
            'nama_kepala_admin' => $this->nama_kepala_admin,
            'nip_kepala_admin' => $this->nip_kepala_admin,
            'catatan_penting' => $this->catatan_penting,
        ];

        // Menangani upload logo jika ada file baru.
        if ($this->logo) {
            // Hapus file logo lama jika ada untuk menghemat storage.
            if ($this->settings->logo && Storage::exists($this->settings->logo)) {
                Storage::delete($this->settings->logo);
            }
            // Simpan file baru dan dapatkan path-nya.
            $dataToUpdate['logo'] = $this->logo->store('surat-penerimaan', 'public');
        }

        // Menangani upload stempel jika ada file baru.
        if ($this->stempel) {
            // Hapus file stempel lama jika ada.
            if ($this->settings->stempel && Storage::exists($this->settings->stempel)) {
                Storage::delete($this->settings->stempel);
            }
            // Simpan file baru dan dapatkan path-nya.
            $dataToUpdate['stempel'] = $this->stempel->store('surat-penerimaan', 'public');
        }

        // Menggunakan updateOrCreate untuk menyimpan data.
        SuratPenerimaanSetting::updateOrCreate(['id' => 1], $dataToUpdate);

        // Menampilkan pesan sukses kepada pengguna.
        session()->flash('message', 'Pengaturan surat penerimaan berhasil disimpan!');

        // Refresh komponen untuk menampilkan data terbaru (misal: preview gambar).
        $this->mount();
    }

    // Metode 'render' yang bertanggung jawab menampilkan view Blade.
    public function render()
    {
        // Mengembalikan view yang akan dirender.
        return view('livewire.admin.psb.sertifikat-penerimaan');
    }
}