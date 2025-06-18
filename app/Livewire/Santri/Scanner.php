<?php

namespace App\Livewire\Santri;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Scanner extends Component
{
    #[Layout('components.layouts.app-mobile')]
    public function render()
    {
        return view('livewire.santri.scanner');
    }
}
