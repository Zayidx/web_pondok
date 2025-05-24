<?php

namespace App\Livewire\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;
use App\Models\PSB\Dokumen;

class DetailRegistration extends Component
{
    public $santri;
    public $wali;
    public $dokumen;
    public $fotoSantri; // Properti baru untuk menyimpan path foto dari dokumen
    public $formPage = 1;

    public function mount($santriId)
    {
        $this->santri = PendaftaranSantri::with(['dokumen'])->findOrFail($santriId);
        $this->wali = WaliSantri::where('pendaftaran_santri_id', $santriId)->firstOrFail();
        $this->dokumen = $this->santri->dokumen ?? collect();

        // Cari dokumen dengan jenis_berkas 'foto'
        $this->fotoSantri = $this->dokumen->where('jenis_berkas', 'foto')->first()?->file_path;
    }

    public function nextForm()
    {
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

    public function render()
    {
        return view('livewire.p-s-b.detail-registration');
    }
}