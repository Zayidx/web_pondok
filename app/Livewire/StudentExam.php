<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StudentExam extends Component
{
    use WithFileUploads;

    public $questions;
    public $showForm = false;
    public $editingQuestion = null;

    // Form Data
    public $type = 'multiple';
    public $question = '';
    public $points = 5;
    public $questionImage;
    public $correctAnswer = '';
    public $options = [];

    // Temporary image storage
    public $tempImages = [];

    protected $rules = [
        'type' => 'required|in:essay,multiple-choice',
        'question' => 'required|string|min:10',
        'points' => 'required|integer|min:1|max:100',
        'questionImage' => 'nullable|image|max:2048',
        'correctAnswer' => 'nullable|string',
        'options.*.text' => 'nullable|string',
        'options.*.isCorrect' => 'boolean',
        'options.*.useImage' => 'boolean',
        'options.*.image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->loadQuestions();
        $this->initializeOptions();
    }

    public function updatedType($value)
    {
        if ($value === 'multiple-choice') {
            $this->initializeOptions();
            $this->correctAnswer = ''; 
        } else {
            $this->options = [];
            $this->correctAnswer = ''; 
        }
    }

    public function loadQuestions()
    {
        $this->questions = Question::orderBy('created_at', 'desc')->get();
    }

    public function initializeOptions()
    {
        logger('Initializing options for multiple choice');
        $this->options = [
            ['id' => 'a', 'text' => '', 'isCorrect' => false, 'useImage' => false, 'image' => null, 'imagePath' => ''],
            ['id' => 'b', 'text' => '', 'isCorrect' => false, 'useImage' => false, 'image' => null, 'imagePath' => ''],
            ['id' => 'c', 'text' => '', 'isCorrect' => false, 'useImage' => false, 'image' => null, 'imagePath' => ''],
            ['id' => 'd', 'text' => '', 'isCorrect' => false, 'useImage' => false, 'image' => null, 'imagePath' => '']
        ];
        $this->correctAnswer = '';
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($questionId)
    {
        $question = Question::find($questionId);
        if (!$question) {
            session()->flash('error', 'Soal tidak ditemukan!');
            return;
        }
        $this->editingQuestion = $question;
        
        $this->type = $question->type;
        $this->question = $question->question;
        $this->points = $question->points;
        $this->correctAnswer = $question->correct_answer ?? '';

        if ($question->type === 'multiple-choice' && $question->options) {
            $this->options = array_map(function ($option, $index) {
                $letters = ['a', 'b', 'c', 'd'];
                return [
                    'id' => $letters[$index] ?? $index,
                    'text' => $option['text'] ?? '',
                    'isCorrect' => $option['isCorrect'] ?? false,
                    'useImage' => $option['useImage'] ?? false,
                    'image' => null,
                    'imagePath' => $option['imagePath'] ?? '',
                ];
            }, $question->options, array_keys($question->options));
        } else {
            $this->initializeOptions();
        }
        
        $this->showForm = true;
    }

    public function delete($questionId)
    {
        $question = Question::find($questionId);
        if (!$question) {
            session()->flash('error', 'Soal tidak ditemukan!');
            return;
        }
        
        if ($question->image_path) {
            Storage::disk('public')->delete($question->image_path);
        }

        if ($question->options) {
            foreach ($question->options as $option) {
                if (isset($option['imagePath']) && $option['imagePath']) {
                    Storage::disk('public')->delete($option['imagePath']);
                }
            }
        }

        $question->delete();
        $this->loadQuestions();

        session()->flash('message', 'Soal berhasil dihapus!');
        $this->dispatchBrowserEvent('questionSaved');
    }

    public function save()
    {
        if ($this->type === 'multiple-choice') {
            $correctOptionExists = false;
            $hasValidOption = false;

            foreach ($this->options as $index => $option) {
                if ($option['useImage']) {
                    if (!$option['image'] && !$option['imagePath']) {
                        throw ValidationException::withMessages([
                            "options.$index.image" => 'Gambar opsi harus diunggah jika menggunakan gambar.',
                        ]);
                    }
                } else {
                    if (empty(trim($option['text']))) {
                        continue; 
                    }
                }
                $hasValidOption = true;
                if ($option['isCorrect']) {
                    $correctOptionExists = true;
                }
            }

            if (!$hasValidOption) {
                throw ValidationException::withMessages([
                    'options' => 'Minimal satu opsi harus diisi (teks atau gambar).',
                ]);
            }
            if (!$correctOptionExists) {
                throw ValidationException::withMessages([
                    'options' => 'Minimal satu opsi harus dipilih sebagai jawaban benar.',
                ]);
            }
        }

        $this->validate();

        $data = [
            'type' => $this->type,
            'question' => $this->question,
            'points' => $this->points,
        ];

        if ($this->questionImage) {
            if ($this->editingQuestion && $this->editingQuestion->image_path) {
                Storage::disk('public')->delete($this->editingQuestion->image_path);
            }
            $imagePath = $this->questionImage->store('questions', 'public');
            $data['image_path'] = $imagePath;
        } else {
            if ($this->editingQuestion && $this->editingQuestion->image_path) {
                $data['image_path'] = $this->editingQuestion->image_path;
            } else {
                $data['image_path'] = null;
            }
        }

        if ($this->type === 'essay') {
            $data['correct_answer'] = $this->correctAnswer;
            $data['options'] = null;
        } elseif ($this->type === 'multiple-choice') {
            $correctOption = collect($this->options)->firstWhere('isCorrect', true);
            $data['correct_answer'] = $correctOption ? $correctOption['id'] : null;

            $processedOptions = [];

            foreach ($this->options as $option) {
                if ($option['useImage']) {
                    if ($this->editingQuestion && $this->editingQuestion->options) {
                        $oldOption = collect($this->editingQuestion->options)->firstWhere('id', $option['id']);
                        if ($option['image'] && $oldOption && $oldOption['imagePath'] && $oldOption['imagePath'] !== $option['image']->getRealPath()) {
                            Storage::disk('public')->delete($oldOption['imagePath']);
                        }
                    }

                    $imagePath = $option['image'] ? $option['image']->store('options', 'public') : $option['imagePath'];

                    $processedOptions[] = [
                        'id' => $option['id'],
                        'text' => '',
                        'isCorrect' => $option['isCorrect'],
                        'useImage' => true,
                        'imagePath' => $imagePath,
                    ];
                } else {
                    if ($this->editingQuestion && $this->editingQuestion->options) {
                        $oldOption = collect($this->editingQuestion->options)->firstWhere('id', $option['id']);
                        if ($oldOption && $oldOption['imagePath']) {
                            Storage::disk('public')->delete($oldOption['imagePath']);
                        }
                    }

                    $processedOptions[] = [
                        'id' => $option['id'],
                        'text' => $option['text'],
                        'isCorrect' => $option['isCorrect'],
                        'useImage' => false,
                        'imagePath' => '',
                    ];
                }
            }

            $data['options'] = $processedOptions;
        }

        if ($this->editingQuestion) {
            $this->editingQuestion->update($data);
            session()->flash('message', 'Soal berhasil diupdate!');
        } else {
            Question::create($data);
            session()->flash('message', 'Soal berhasil ditambahkan!');
        }

        $this->resetForm();
        $this->loadQuestions();

        $this->emit('questionSaved');
    }

    public function toggleOptionType($index)
    {
        $this->options[$index]['useImage'] = !$this->options[$index]['useImage'];

        if ($this->options[$index]['useImage']) {
            $this->options[$index]['text'] = '';
        } else {
            $this->options[$index]['image'] = null;
            $this->options[$index]['imagePath'] = '';
        }
    }

    public function setCorrectAnswer($index)
    {
        foreach ($this->options as $key => $option) {
            $this->options[$key]['isCorrect'] = ($key === $index);
        }
    }

    public function removeQuestionImage()
    {
        if ($this->editingQuestion && $this->editingQuestion->image_path) {
            Storage::disk('public')->delete($this->editingQuestion->image_path);
        }
        $this->questionImage = null;
    }

    public function removeOptionImage($index)
    {
        if ($this->editingQuestion && $this->editingQuestion->options) {
            $oldOption = collect($this->editingQuestion->options)->firstWhere('id', $this->options[$index]['id']);
            if ($oldOption && $oldOption['imagePath']) {
                Storage::disk('public')->delete($oldOption['imagePath']);
            }
        }
        $this->options[$index]['image'] = null;
        $this->options[$index]['imagePath'] = '';
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editingQuestion = null;
        $this->type = 'essay';
        $this->question = '';
        $this->points = 5;
        $this->questionImage = null;
        $this->correctAnswer = '';
        $this->initializeOptions();
        $this->tempImages = [];
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.student-exam');
    }
}
