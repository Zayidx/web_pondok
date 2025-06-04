<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\Soal;
use App\Models\PSB\Ujian;
use Livewire\Component;
use Livewire\Attributes\Title;

/**
 * PreviewUjian Component
 * 
 * Komponen ini menampilkan preview ujian dari perspektif santri,
 * menampilkan soal-soal dan opsi jawaban seperti yang akan dilihat santri
 */
class PreviewUjian extends Component
{
    #[Title('Preview Ujian')]
    
    public $ujianId;
    public $ujian;

    /**
     * Inisialisasi komponen
     * 
     * @param int $ujianId ID ujian yang akan dipreview
     */
    public function mount($ujianId)
    {
        $this->ujianId = $ujianId;
        $this->ujian = Ujian::with('soals')->findOrFail($ujianId);
    }

    /**
     * Render view untuk komponen ini
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.psb.preview-ujian');
    }
} 