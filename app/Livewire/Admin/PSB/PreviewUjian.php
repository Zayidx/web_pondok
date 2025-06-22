<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\Ujian;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.ujian')] // Sets the Blade layout to be used.
#[Title('Preview Ujian')]
class PreviewUjian extends Component
{
    public $ujian;
    public $soals;
    public $jumlahSoal;
    public $currentPage = 1;

    public function mount($ujianId)
    {
        $this->ujian = Ujian::with(['soals' => function ($query) {
            $query->orderByRaw("CASE WHEN tipe_soal = 'pg' THEN 0 ELSE 1 END")
                  ->orderBy('created_at', 'asc');
        }])->findOrFail($ujianId);

        $this->soals = $this->ujian->soals;
        $this->jumlahSoal = $this->soals->count();
    }

    public function gotoPage($pageNumber)
    {
        if ($pageNumber >= 1 && $pageNumber <= $this->jumlahSoal) {
            $this->currentPage = $pageNumber;
        }
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->jumlahSoal) {
            $this->currentPage++;
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function render()
    {
        $currentSoal = $this->soals[$this->currentPage - 1] ?? null;

        return view('livewire.admin.psb.preview-ujian', [
            'currentSoal' => $currentSoal,
        ]);
    }
}