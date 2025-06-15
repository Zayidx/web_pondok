<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Attributes\Layout;
use Livewire\Component;

class PsbPage extends Component
{
    #[Layout('components.layouts.ppdb-page')]
    public function render()
    {
        return view('livewire.admin.psb.psb-page');
    }
}
