<?php

namespace App\Livewire\PSB;

use App\Models\PSB\HasilUjian; // Menggunakan model HasilUjian dari namespace PSB
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class SantriDashboard extends Component
{
    use WithPagination;
    #[Title('Dashboard Santri')]
    #[Layout('components.layouts.pendaftaran-santri-app')]

    #[Computed]
    public function listHasilUjian()
    {
        // Memuat ujian dengan select hanya kolom yang diperlukan
        return HasilUjian::where('santri_id', auth()->guard('santri')->user()->id)
            ->with(['ujian' => function ($query) {
                $query->select('id', 'nama_ujian', 'mata_pelajaran', 'tanggal_ujian');
            }])
            ->orderBy('created_at', 'desc') // Tambahkan pengurutan terbaru
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.psb.santri-dashboard');
    }
}