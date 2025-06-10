<?php

namespace App\Livewire\Admin\PSB;

use App\Models\PSB\Soal;
use App\Models\PSB\Ujian;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

/**
 * PreviewUjian Component
 * 
 * Komponen ini menampilkan preview ujian dari perspektif santri,
 * menampilkan soal-soal dan opsi jawaban seperti yang akan dilihat santri
 */
class PreviewUjian extends Component
{
    #[Title('Preview Ujian')]
    #[Layout('components.layouts.preview-ujian')]
    
    public $ujianId;
    public $ujian;
    public $currentQuestionIndex = 0;
    public $questions;

    public function mount($ujianId)
    {
        $this->ujianId = $ujianId;
        $this->ujian = Ujian::with('soals')->findOrFail($ujianId);
        $this->loadQuestions();
    }

    protected function loadQuestions()
    {
        // Cache the questions for 5 minutes to improve performance
        $this->questions = cache()->remember("ujian_{$this->ujianId}_questions", 300, function () {
            return $this->ujian->soals()
                ->orderByRaw("CASE WHEN tipe_soal = 'pg' THEN 0 ELSE 1 END")
                ->orderBy('created_at', 'asc')
                ->get();
        });
    }

    #[Computed]
    public function currentQuestion()
    {
        return $this->questions[$this->currentQuestionIndex] ?? null;
    }

    #[Computed]
    public function totalQuestions()
    {
        return $this->questions->count();
    }

    #[Computed]
    public function progress()
    {
        if ($this->totalQuestions() === 0) return 0;
        return ($this->currentQuestionIndex + 1) / $this->totalQuestions() * 100;
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->totalQuestions() - 1) {
            $this->currentQuestionIndex++;
            $this->dispatchBrowserEvent('question-changed');
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->dispatchBrowserEvent('question-changed');
        }
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < $this->totalQuestions()) {
            $this->currentQuestionIndex = $index;
            $this->dispatchBrowserEvent('question-changed');
        }
    }

    /**
     * Render view untuk komponen ini
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.psb.preview-ujian', [
            'currentQuestion' => $this->currentQuestion()
        ]);
    }
} 