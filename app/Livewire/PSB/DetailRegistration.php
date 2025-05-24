<?php

namespace App\Livewire\PSB;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\WaliSantri;

class DetailRegistration extends Component
{
    public $santri;
    public $wali;
    public $formPage = 1;

    public function mount($santriId)
    {
        $this->santri = PendaftaranSantri::findOrFail($santriId);
        $this->wali = WaliSantri::where('pendaftaran_santri_id', $santriId)->firstOrFail();
    }

    public function nextForm()
    {
        if ($this->formPage < 2) {
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