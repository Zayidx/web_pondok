<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PSB\SuratPenerimaanSetting as SuratPenerimaanSettingModel;
use Illuminate\Support\Facades\Storage;

/**
 * Livewire component untuk mengelola pengaturan surat penerimaan santri.
 * Component ini menangani form pengaturan dan preview surat penerimaan,
 * termasuk upload logo dan stempel serta manajemen catatan penting.
 */
class SuratPenerimaanSettingForm extends Component
{
    use WithFileUploads;

    /** @var \App\Models\PSB\SuratPenerimaanSetting */
    public $settings;
    
    /** @var \Livewire\TemporaryUploadedFile|null Logo pesantren yang diupload */
    public $logo;
    
    /** @var \Livewire\TemporaryUploadedFile|null Stempel resmi yang diupload */
    public $stempel;
    
    /** @var array Daftar catatan penting */
    public $catatan = [];
    
    // Fields untuk form
    /** @var string Nama pesantren */
    public $nama_pesantren;
    /** @var string Nama yayasan */
    public $nama_yayasan;
    /** @var string Alamat pesantren */
    public $alamat_pesantren;
    /** @var string Nomor telepon */
    public $telepon_pesantren;
    /** @var string Email pesantren */
    public $email_pesantren;
    /** @var string Nama direktur */
    public $nama_direktur;
    /** @var string NIP direktur */
    public $nip_direktur;
    /** @var string Nama kepala admin */
    public $nama_kepala_admin;
    /** @var string NIP kepala admin */
    public $nip_kepala_admin;
    /** @var string Catatan baru yang akan ditambahkan */
    public $new_catatan;

    /**
     * Aturan validasi untuk form pengaturan.
     *
     * @var array
     */
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
    ];

    /**
     * Inisialisasi component dan memuat data pengaturan yang ada.
     *
     * @return void
     */
    public function mount()
    {
        $this->settings = SuratPenerimaanSettingModel::first() ?? new SuratPenerimaanSettingModel();
        $this->loadSettings();
    }

    /**
     * Memuat data pengaturan ke dalam properti component.
     *
     * @return void
     */
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
            $this->catatan = $this->settings->catatan_penting ?? [];
        }
    }

    /**
     * Menambahkan catatan penting baru ke daftar.
     *
     * @return void
     */
    public function addCatatan()
    {
        if ($this->new_catatan) {
            $this->catatan[] = $this->new_catatan;
            $this->new_catatan = '';
        }
    }

    /**
     * Menghapus catatan penting dari daftar berdasarkan indeks.
     *
     * @param int $index Indeks catatan yang akan dihapus
     * @return void
     */
    public function removeCatatan($index)
    {
        unset($this->catatan[$index]);
        $this->catatan = array_values($this->catatan);
    }

    /**
     * Menyimpan pengaturan surat penerimaan.
     * Menangani upload file logo dan stempel serta menyimpan semua data ke database.
     *
     * @return void
     */
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
            'catatan_penting' => $this->catatan,
        ]);

        $this->settings->save();

        session()->flash('message', 'Pengaturan surat penerimaan berhasil disimpan!');
    }

    /**
     * Render tampilan component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.psb.surat-penerimaan-setting');
    }
} 