<?php

namespace App\Livewire\Admin\PSB;

use Livewire\Attributes\Title;
use Livewire\Component;

class Dashboard extends Component
{
    #[Title('Dashboard Pendaftaran Santri Baru')]

    public function render()
    {
        return view('livewire.admin.psb.dashboard');
    }
}

