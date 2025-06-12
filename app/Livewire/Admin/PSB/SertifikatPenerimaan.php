<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\SertifikatTemplate;
use Livewire\Attributes\Title;
use Livewire\Attributes\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

#[Title('Pengaturan Sertifikat Penerimaan')]
#[Middleware(['auth', 'role:Pendaftaran Santri'])]
class SertifikatPenerimaan extends Component
{
    public $nama_pesantren;
    public $nama_yayasan;
    public $alamat_pesantren;
    public $nomor_telepon;
    public $email_pesantren;
    public $catatan_penting;
    public $nama_direktur;
    public $nip_direktur;
    public $nama_kepala_admin;
    public $nip_kepala_admin;

    public function mount()
    {
        $template = SertifikatTemplate::first();
        if ($template) {
            $this->nama_pesantren = $template->nama_pesantren;
            $this->nama_yayasan = $template->nama_yayasan;
            $this->alamat_pesantren = $template->alamat_pesantren;
            $this->nomor_telepon = $template->nomor_telepon;
            $this->email_pesantren = $template->email_pesantren;
            $this->catatan_penting = $template->catatan_penting;
            $this->nama_direktur = $template->nama_direktur;
            $this->nip_direktur = $template->nip_direktur;
            $this->nama_kepala_admin = $template->nama_kepala_admin;
            $this->nip_kepala_admin = $template->nip_kepala_admin;
        }
    }

    public function save()
    {
        $this->validate([
            'nama_pesantren' => 'required',
            'nama_yayasan' => 'required',
            'alamat_pesantren' => 'required',
            'nomor_telepon' => 'required',
            'email_pesantren' => 'required|email',
            'catatan_penting' => 'required',
            'nama_direktur' => 'required',
            'nip_direktur' => 'required',
            'nama_kepala_admin' => 'required',
            'nip_kepala_admin' => 'required',
        ]);

        SertifikatTemplate::updateOrCreate(
            ['id' => 1],
            [
                'nama_pesantren' => $this->nama_pesantren,
                'nama_yayasan' => $this->nama_yayasan,
                'alamat_pesantren' => $this->alamat_pesantren,
                'nomor_telepon' => $this->nomor_telepon,
                'email_pesantren' => $this->email_pesantren,
                'catatan_penting' => $this->catatan_penting,
                'nama_direktur' => $this->nama_direktur,
                'nip_direktur' => $this->nip_direktur,
                'nama_kepala_admin' => $this->nama_kepala_admin,
                'nip_kepala_admin' => $this->nip_kepala_admin,
            ]
        );

        session()->flash('message', 'Template sertifikat berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.psb.sertifikat-penerimaan')
            ->layout('components.layouts.app');
    }
} 