<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use Livewire\Attributes\Title;

class DashboardUjianSantri extends Component
{
    #[Title('Dashboard Ujian Santri')]

    public function render()
    {
        return view('livewire.santri-p-p-d-b.dashboard-ujian-santri');
    }
} 