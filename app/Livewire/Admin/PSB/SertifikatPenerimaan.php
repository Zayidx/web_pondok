<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PSB\SuratPenerimaanSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;

class SertifikatPenerimaan extends Component
{
    use WithFileUploads;
    #[Title('Pengaturan Template Sertifikat')]
    public $settings;
    public $logo;
  public $ttd_admin;
  public $ttd_direktur;
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
    public $activeTab = 'umum';
    protected $rules = [
        'nama_pesantren' => 'required',
        'nama_yayasan' => 'required',
        'alamat_pesantren' => 'required',
        'telepon_pesantren' => 'required',
        'email_pesantren' => 'required|email',
        'logo' => 'nullable|image|max:2048', // max 1MB
        'ttd_direktur' => 'nullable|image|max:1024',
        'ttd_admin' => 'nullable|image|max:1024',

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

    // CONTOH IMPLEMENTASI METHOD SAVE (PENDEKATAN 1)
    public function save()
    {
        $this->validate();
        
        // Handle logo upload dan langsung update properti model.
        if ($this->logo) {
            if ($this->settings->logo) {
                Storage::delete($this->settings->logo);
            }
            // Langsung set properti 'logo' pada model.
            $this->settings->logo = $this->logo->store('public/surat-penerimaan');
        }
    
        
        if ($this->ttd_direktur) {
            if ($this->settings->ttd_direktur) {
                Storage::delete($this->settings->ttd_direktur);
            }
            // Langsung set properti 'ttd_direktur' pada model.
            $this->settings->ttd_direktur = $this->ttd_direktur->store('public/surat-penerimaan');
        }
         if ($this->ttd_admin) {
            if ($this->settings->ttd_admin) {
                Storage::delete($this->settings->ttd_admin);
            }
            // Langsung set properti 'ttd_admin' pada model.
            $this->settings->ttd_admin = $this->ttd_admin->store('public/surat-penerimaan');
        }
    
        $catatan = null;
        if ($this->catatan) {
            $catatan = preg_replace('/^\d+\.\s*/m', '', $this->catatan);
            $catatan = preg_replace('/,\s*$/', '', $catatan);
            $catatan = str_replace(',', "\n", $catatan);
            $catatan = preg_replace('/\n\s*\n/', "\n", $catatan);
            $catatan = trim($catatan);
        }
        // Langsung set properti 'catatan_penting' pada model.
        $this->settings->catatan_penting = $catatan;
    
        // Gunakan fill() untuk sisa field yang lain.
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
        ]);
    
        $this->settings->save();
    
        session()->flash('message', 'Pengaturan surat penerimaan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.psb.sertifikat-penerimaan');
    }
} 