<?php

namespace App\Livewire\SantriPPDB;

use Livewire\Component;
use App\Models\HasilUjian;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class RiwayatUjian extends Component
{
    use WithPagination;

    #[Layout('components.layouts.ujian')]
    #[Title('Riwayat Ujian')]

    public $mataPelajaran = '';
    public $statusFilter = '';
    public $mataPelajaranList = [];
    public $stats = [];

    public function mount()
    {
        if (!Auth::guard('santri')->check()) {
            abort(403, 'Unauthorized');
        }

        $this->mataPelajaranList = Ujian::join('hasil_ujians', 'ujians.id', '=', 'hasil_ujians.ujian_id')
            ->where('hasil_ujians.santri_id', Auth::guard('santri')->id())
            ->distinct()
            ->pluck('ujians.mata_pelajaran')
            ->toArray();

        $this->stats = HasilUjian::where('santri_id', Auth::guard('santri')->id())
            ->selectRaw('
                COUNT(*) as total_ujian,
                SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = "menunggu" THEN 1 ELSE 0 END) as menunggu,
                AVG(CASE WHEN nilai_akhir IS NOT NULL THEN nilai_akhir ELSE NULL END) as rata_rata
            ')
            ->first() ?? (object) [
                'total_ujian' => 0,
                'selesai' => 0,
                'menunggu' => 0,
                'rata_rata' => 0,
            ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'mataPelajaran' => 'nullable|string',
            'statusFilter' => 'nullable|in:selesai,menunggu',
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['mataPelajaran', 'statusFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $query = HasilUjian::where('santri_id', Auth::guard('santri')->id())
            ->with('ujian');

        if (!empty($this->mataPelajaran)) {
            $query->whereHas('ujian', function($q) {
                $q->where('mata_pelajaran', $this->mataPelajaran);
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $hasilUjian = $query->latest()->paginate(10);

        return view('livewire.santri-p-p-d-b.riwayat-ujian', [
            'hasilUjian' => $hasilUjian,
            'stats' => $this->stats,
        ]);
    }
}