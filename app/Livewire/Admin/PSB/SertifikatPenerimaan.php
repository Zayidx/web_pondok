<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PSB\SuratPenerimaanSetting;
use Illuminate\Support\Facades\Storage;

class SertifikatPenerimaan extends Component
{
    use WithFileUploads;

    public $settings;
    public $logo;
    public $stempel;
    public $catatan;

    // Form fields
    public $nama_pesantren;
    public $nama_yayasan;
    public $alamat_pesantren;
    public $telepon_pesantren;
    public $email_pesantren;
    public $nama_direktur;
    public $nip_direktur;
    public $nama_kepala_admin;
    public $nip_kepala_admin;

    protected $rules = [
        'nama_pesantren' => 'required',
        'nama_yayasan' => 'required',
        'alamat_pesantren' => 'required',
        'telepon_pesantren' => 'required',
        'email_pesantren' => 'required|email',
        'logo' => 'nullable|image|max:1024', // max 1MB
        'stempel' => 'nullable|image|max:1024',
        'nama_direktur' => 'required',
        'nip_direktur' => 'required',
        'nama_kepala_admin' => 'required',
        'nip_kepala_admin' => 'required',
        'catatan' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'catatan.max' => 'Catatan tidak boleh lebih dari 1000 karakter',
        'catatan.string' => 'Catatan harus berupa teks',
    ];

    public function mount()
    {
        $this->settings = SuratPenerimaanSetting::first() ?? new SuratPenerimaanSetting();
        $this->loadSettings();
    }

    public function loadSettings()
    {
        if ($this->settings->exists) {
            $this->nama_pesantren = $this->settings->nama_pesantren;
            $this->nama_yayasan = $this->settings->nama_yayasan;
            $this->alamat_pesantren = $this->settings->alamat_pesantren;
            $this->telepon_pesantren = $this->settings->telepon_pesantren;
            $this->email_pesantren = $this->settings->email_pesantren;
            $this->nama_direktur = $this->settings->nama_direktur;
            $this->nip_direktur = $this->settings->nip_direktur;
            $this->nama_kepala_admin = $this->settings->nama_kepala_admin;
            $this->nip_kepala_admin = $this->settings->nip_kepala_admin;
            $this->catatan = $this->settings->catatan_penting;
        }
    }

    public function save()
    {
        $this->validate();

        // Handle logo upload
        if ($this->logo) {
            if ($this->settings->logo) {
                Storage::delete($this->settings->logo);
            }
            $logoPath = $this->logo->store('public/surat-penerimaan');
            $this->settings->logo = $logoPath;
        }

        // Handle stempel upload
        if ($this->stempel) {
            if ($this->settings->stempel) {
                Storage::delete($this->settings->stempel);
            }
            $stempelPath = $this->stempel->store('public/surat-penerimaan');
            $this->settings->stempel = $stempelPath;
        }

        // Format catatan jika ada
        if ($this->catatan) {
            // Hapus nomor dan titik di awal baris jika ada
            $catatan = preg_replace('/^\d+\.\s*/m', '', $this->catatan);
            // Hapus koma di akhir baris
            $catatan = preg_replace('/,\s*$/', '', $catatan);
            // Ganti koma dengan baris baru
            $catatan = str_replace(',', "\n", $catatan);
            // Hapus baris kosong berlebih
            $catatan = preg_replace('/\n\s*\n/', "\n", $catatan);
            // Trim whitespace
            $catatan = trim($catatan);
        }

        // Update other fields
        $this->settings->fill([
                'nama_pesantren' => $this->nama_pesantren,
                'nama_yayasan' => $this->nama_yayasan,
                'alamat_pesantren' => $this->alamat_pesantren,
            'telepon_pesantren' => $this->telepon_pesantren,
                'email_pesantren' => $this->email_pesantren,
                'nama_direktur' => $this->nama_direktur,
                'nip_direktur' => $this->nip_direktur,
                'nama_kepala_admin' => $this->nama_kepala_admin,
                'nip_kepala_admin' => $this->nip_kepala_admin,
            'catatan_penting' => $catatan ?? null,
        ]);

        $this->settings->save();

        session()->flash('message', 'Pengaturan surat penerimaan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.psb.sertifikat-penerimaan');
    }
} 