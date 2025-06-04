<?php

namespace App\Livewire\SantriPPDB;

use App\Models\HasilUjian;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class SantriDashboard extends Component
{
    use WithPagination;
    #[Title('Dashboard Santri')]

    #[Computed]
    public function listHasilUjian()
    {
        return HasilUjian::where('santri_id', auth()->guard('santri')->user()->id)
            ->with(['ujian' => function ($query) {
                $query->select('id', 'nama_ujian', 'mata_pelajaran', 'tanggal_ujian');
            }])
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.santri-ppdb.santri-dashboard');
    }
}