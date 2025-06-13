<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\SuratPenerimaanSetting as SuratPenerimaanSettingModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SuratPenerimaanSettingForm extends Component
{
    use WithFileUploads;

    public $settings;
    public $logo;
    public $stempel;
    public $nama_pesantren;
    public $nama_yayasan;
    public $alamat_pesantren;
    public $telepon_pesantren;
    public $email_pesantren;
    public $nama_direktur;
    public $nip_direktur;
    public $nama_kepala_admin;
    public $nip_kepala_admin;
    public $catatan_penting;
    public $tahun_ajaran;
    public $tanggal_orientasi;
    public $batas_pembayaran_spp;

    protected $rules = [
        'nama_pesantren' => 'required',
        'nama_yayasan' => 'required',
        'alamat_pesantren' => 'required',
        'telepon_pesantren' => 'required',
        'email_pesantren' => 'required|email',
        'nama_direktur' => 'required',
        'nip_direktur' => 'required',
        'nama_kepala_admin' => 'required',
        'nip_kepala_admin' => 'required',
        'tahun_ajaran' => 'required',
        'tanggal_orientasi' => 'required|date',
        'batas_pembayaran_spp' => 'required|date',
        'catatan_penting' => 'required',
        'logo' => 'nullable|image|max:1024',
        'stempel' => 'nullable|image|max:1024',
    ];

    public function mount()
    {
        $this->settings = SuratPenerimaanSettingModel::first();
        if ($this->settings) {
            $this->nama_pesantren = $this->settings->nama_pesantren;
            $this->nama_yayasan = $this->settings->nama_yayasan;
            $this->alamat_pesantren = $this->settings->alamat_pesantren;
            $this->telepon_pesantren = $this->settings->telepon_pesantren;
            $this->email_pesantren = $this->settings->email_pesantren;
            $this->nama_direktur = $this->settings->nama_direktur;
            $this->nip_direktur = $this->settings->nip_direktur;
            $this->nama_kepala_admin = $this->settings->nama_kepala_admin;
            $this->nip_kepala_admin = $this->settings->nip_kepala_admin;
            $this->tahun_ajaran = $this->settings->tahun_ajaran;
            $this->tanggal_orientasi = $this->settings->tanggal_orientasi;
            $this->batas_pembayaran_spp = $this->settings->batas_pembayaran_spp;
            $this->catatan_penting = $this->settings->catatan_penting;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama_pesantren' => $this->nama_pesantren,
            'nama_yayasan' => $this->nama_yayasan,
            'alamat_pesantren' => $this->alamat_pesantren,
            'telepon_pesantren' => $this->telepon_pesantren,
            'email_pesantren' => $this->email_pesantren,
            'nama_direktur' => $this->nama_direktur,
            'nip_direktur' => $this->nip_direktur,
            'nama_kepala_admin' => $this->nama_kepala_admin,
            'nip_kepala_admin' => $this->nip_kepala_admin,
            'tahun_ajaran' => $this->tahun_ajaran,
            'tanggal_orientasi' => $this->tanggal_orientasi,
            'batas_pembayaran_spp' => $this->batas_pembayaran_spp,
            'catatan_penting' => $this->catatan_penting,
        ];

        if ($this->logo) {
            if ($this->settings && $this->settings->logo) {
                Storage::delete($this->settings->logo);
            }
            $data['logo'] = $this->logo->store('public/surat-penerimaan');
        }

        if ($this->stempel) {
            if ($this->settings && $this->settings->stempel) {
                Storage::delete($this->settings->stempel);
            }
            $data['stempel'] = $this->stempel->store('public/surat-penerimaan');
        }

        if ($this->settings) {
            $this->settings->update($data);
        } else {
            SuratPenerimaanSettingModel::create($data);
        }

        session()->flash('message', 'Pengaturan berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.admin.psb.surat-penerimaan-setting');
    }
} 