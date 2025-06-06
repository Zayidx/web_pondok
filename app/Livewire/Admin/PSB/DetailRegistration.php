<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;
use App\Models\PSB\Dokumen;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class DetailRegistration extends Component
{
    #[Layout('components.layouts.app')]
    #[Title('Detail Pendaftaran Santri')]

    public $santri;
    public $wali;
    public $dokumen;
    public $fotoSantri;
    public $formPage = 1;

    public function mount($santriId)
    {
        // Load santri with relationships
        $this->santri = PendaftaranSantri::with(['dokumen', 'wali'])->findOrFail($santriId);
        
        // Use empty WaliSantri object if wali doesn't exist
        $this->wali = $this->santri->wali ?? new WaliSantri();
        
        // Use empty collection if no documents
        $this->dokumen = $this->santri->dokumen ?? collect();
        
        // Check for photo, set to null if doesn't exist
        $fotoDokumen = $this->dokumen->where('jenis_berkas', 'Pas Foto')->first();
        $this->fotoSantri = $fotoDokumen ? $fotoDokumen->file_path : null;
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
        return view('livewire.admin.psb.detail-registration');
    }
}