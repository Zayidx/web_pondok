<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\HasilUjian;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class RiwayatUjian extends Component
{
    use WithPagination;

    #[Layout('components.layouts.ujian')]
    #[Title('Riwayat Ujian')]

    public function render()
    {
        $hasilUjian = HasilUjian::where('santri_id', Auth::guard('santri')->id())
            ->with('ujian')
            ->latest()
            ->paginate(10);

        return view('livewire.santri-p-p-d-b.riwayat-ujian', [
            'hasilUjian' => $hasilUjian
        ]);
    }
} 