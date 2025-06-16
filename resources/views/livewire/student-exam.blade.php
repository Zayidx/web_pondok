<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Bank Soal</h1>
                    <p class="text-muted">Kelola soal essay dan pilihan ganda</p>
                </div>
                <button wire:click="showCreateForm" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Soal
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <div class="bg-primary rounded" style="width: 24px; height: 24px;"></div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total Soal</p>
                            <h3 class="mb-0">{{ $questions->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <div class="bg-success rounded" style="width: 24px; height: 24px;"></div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Soal Essay</p>
                            <h3 class="mb-0">{{ $questions->where('type', 'essay')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded p-3 me-3">
                            <div class="bg-info rounded" style="width: 24px; height: 24px;"></div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Pilihan Ganda</p>
                            <h3 class="mb-0">{{ $questions->where('type', 'multiple-choice')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    @if($showForm)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    {{ $editingQuestion ? 'Edit Soal' : 'Tambah Soal Baru' }}
                </h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <!-- Question Type -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Tipe Soal</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" wire:model="type" value="essay" id="typeEssay">
                                <label class="form-check-label" for="typeEssay">Essay</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" wire:model="type" value="multiple-choice" id="typeMultiple">
                                <label class="form-check-label" for="typeMultiple">Pilihan Ganda</label>
                            </div>
                        </div>
                        @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <!-- Question Text -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Pertanyaan <span class="text-danger">*</span></label>
                        <textarea wire:model="question" class="form-control" rows="3" placeholder="Masukkan pertanyaan..."></textarea>
                        @error('question') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <!-- Question Image -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Gambar Soal (Opsional)</label>
                        <div class="row">
                            <div class="col">
                                <input type="file" wire:model="questionImage" accept="image/*" class="form-control">
                            </div>
                            @if($questionImage)
                                <div class="col-auto">
                                    <button type="button" wire:click="removeQuestionImage" class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        @if($questionImage)
                            <div class="mt-3 p-3 border rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-image text-muted me-2"></i>
                                    <small class="text-muted">{{ $questionImage->getClientOriginalName() }}</small>
                                </div>
                                <img src="{{ $questionImage->temporaryUrl() }}" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        @endif
                        @error('questionImage') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <!-- Points -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">Poin</label>
                        <input type="number" wire:model="points" min="1" max="100" class="form-control" style="width: 120px;">
                        @error('points') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <!-- Essay Answer -->
                    @if($type === 'essay')
                        <div class="mb-3">
                            <label class="form-label fw-medium">Kunci Jawaban (Opsional)</label>
                            <textarea wire:model="correctAnswer" class="form-control" rows="3" placeholder="Masukkan kunci jawaban atau poin-poin penting..."></textarea>
                        </div>
                    @endif

                    <!-- Multiple Choice Options -->
                    @if($type === 'multiple-choice')
<div class="mb-3">
    <label class="form-label fw-medium">Pilihan Jawaban</label>
    @foreach($options as $index => $option)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" 
                                   wire:click="setCorrectAnswer({{ $index }})" 
                                   {{ $option['isCorrect'] ? 'checked' : '' }} 
                                   id="correct{{ $index }}" name="correctAnswer">
                            <label class="form-check-label" for="correct{{ $index }}">Benar</label>
                        </div>
                        <span class="fw-medium">{{ strtoupper($option['id']) }}.</span>
                    </div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" wire:click="setOptionType({{ $index }}, 'text')" 
                                class="btn {{ !$option['useImage'] ? 'btn-primary' : 'btn-outline-secondary' }}">
                            <i class="fas fa-font me-1"></i>Teks
                        </button>
                        <button type="button" wire:click="setOptionType({{ $index }}, 'image')" 
                                class="btn {{ $option['useImage'] ? 'btn-primary' : 'btn-outline-secondary' }}">
                            <i class="fas fa-image me-1"></i>Gambar
                        </button>
                    </div>
                </div>

                @if(!$option['useImage'])
                    <div class="ms-4">
                        <input type="text" wire:model="options.{{ $index }}.text" 
                               class="form-control" placeholder="Pilihan {{ strtoupper($option['id']) }}">
                    </div>
                @else
                    <div class="ms-4">
                        <div class="row">
                            <div class="col">
                                <input type="file" wire:model="options.{{ $index }}.image" 
                                       accept="image/*" class="form-control">
                            </div>
                            @if($option['image'] || $option['imagePath'])
                                <div class="col-auto">
                                    <button type="button" wire:click="removeOptionImage({{ $index }})" 
                                            class="btn btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        @if($option['image'])
                            <div class="mt-2 p-2 border rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-image text-muted me-2"></i>
                                    <small class="text-muted">{{ $option['image']->getClientOriginalName() }}</small>
                                </div>
                                <img src="{{ $option['image']->temporaryUrl() }}" alt="Preview" 
                                     class="img-fluid rounded" style="max-height: 100px;">
                            </div>
                        @elseif($option['imagePath'])
                            <div class="mt-2 p-2 border rounded">
                                <img src="{{ asset('storage/' . $option['imagePath']) }}" 
                                     alt="Option Image" class="img-fluid rounded" style="max-height: 100px;">
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endforeach
    <small class="text-muted">Pilih radio button untuk menandai jawaban yang benar</small>
</div>
@endif

                    {{-- @if($type === 'multiple-choice')
                        <div class="mb-3">
                            <label class="form-label fw-medium">Pilihan Jawaban</label>
                            @foreach($options as $index => $option)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="radio" wire:click="setCorrectAnswer({{ $index }})" 
                                                           {{ $option['isCorrect'] ? 'checked' : '' }} id="correct{{ $index }}">
                                                </div>
                                                <span class="fw-medium">{{ strtoupper($option['id']) }}.</span>
                                            </div>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" wire:click="setOptionType({{ $index }}, 'text')" 
                                                    class="btn {{ !$option['useImage'] ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                    <i class="fas fa-font me-1"></i> Teks
                                                </button>
                                                <button button type="button" wire:click="setOptionType({{ $index }}, 'image')" 
                                                    class="btn {{ $option['useImage'] ? 'btn-primary' : 'btn-outline-secondary' }}">
                                                     <i class="fas fa-image me-1"></i> Gambar
                                                </button>
                                            </div>
                                        </div>

                                        @if(!$option['useImage'])
                                            <div class="ms-4">
                                                <input type="text" wire:model="options.{{ $index }}.text" 
                                                       class="form-control" placeholder="Pilihan {{ strtoupper($option['id']) }}">
                                            </div>
                                        @else
                                            <div class="ms-4">
                                                <div class="row">
                                                    <div class="col">
                                                        <input type="file" wire:model="options.{{ $index }}.image" accept="image/*" class="form-control">
                                                    </div>
                                                    @if($option['image'] || $option['imagePath'])
                                                        <div class="col-auto">
                                                            <button type="button" wire:click="removeOptionImage({{ $index }})" class="btn btn-outline-danger">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($option['image'])
                                                    <div class="mt-2 p-2 border rounded">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-image text-muted me-2"></i>
                                                            <small class="text-muted">{{ $option['image']->getClientOriginalName() }}</small>
                                                        </div>
                                                        <img src="{{ $option['image']->temporaryUrl() }}" alt="Preview" class="img-fluid rounded" style="max-height: 100px;">
                                                    </div>
                                                @elseif($option['imagePath'])
                                                    <div class="mt-2 p-2 border rounded">
                                                        <img src="{{ asset('storage/' . $option['imagePath']) }}" alt="Option Image" class="img-fluid rounded" style="max-height: 100px;">
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            <small class="text-muted">Pilih radio button untuk menandai jawaban yang benar</small>
                        </div>
                    @endif --}}

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            {{ $editingQuestion ? 'Update' : 'Simpan' }}
                        </button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Questions List -->
    @if($questions->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="text-muted mb-4">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="fas fa-plus fa-2x"></i>
                    </div>
                </div>
                <h5 class="mb-2">Belum ada soal</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan soal pertama Anda</p>
                <button wire:click="showCreateForm" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Tambah Soal
                </button>
            </div>
        </div>
    @else
        @foreach($questions as $index => $question)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="h5 text-muted mb-0">#{{ $index + 1 }}</span>
                            <span class="badge {{ $question->type === 'essay' ? 'bg-primary' : 'bg-info' }}">
                                {{ $question->type === 'essay' ? 'Essay' : 'Pilihan Ganda' }}
                            </span>
                            <span class="badge bg-secondary">{{ $question->points }} poin</span>
                            @if($question->image_path)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-image me-1"></i>Ada Gambar
                                </span>
                            @endif
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button wire:click="edit({{ $question->id }})" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="delete({{ $question->id }})" 
                                    wire:confirm="Apakah Anda yakin ingin menghapus soal ini?"
                                    class="btn btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="fw-medium mb-2">Pertanyaan:</h6>
                        <p class="mb-0">{{ $question->question }}</p>
                        @if($question->image_path)
                            <div class="mt-3 p-3 border rounded">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-image text-muted me-2"></i>
                                    <small class="text-muted">Gambar Soal</small>
                                </div>
                                <img src="{{ $question->image_url }}" alt="Gambar soal" class="img-fluid rounded" style="max-height: 300px;">
                            </div>
                        @endif
                    </div>

                    @if($question->type === 'essay' && $question->correct_answer)
                        <div class="alert alert-success">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-key me-2"></i>Kunci Jawaban:
                            </h6>
                            <p class="mb-0">{{ $question->correct_answer }}</p>
                        </div>
                    @endif

                    @if($question->type === 'multiple-choice' && $question->options)
                        <div>
                            <h6 class="fw-medium mb-3">Pilihan Jawaban:</h6>
                            <div class="row">
                                @foreach($question->options as $option)
                                    <div class="col-12 mb-2">
                                        <div class="p-3 border rounded {{ $option['isCorrect'] ? 'border-success bg-success bg-opacity-10' : '' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <span class="fw-medium me-2">{{ strtoupper($option['id']) }}.</span>
                                                    @if($option['useImage'] && isset($option['imagePath']))
                                                        <span class="badge bg-info me-2">
                                                            <i class="fas fa-image me-1"></i>Gambar
                                                        </span>
                                                    @else
                                                        <span>{{ $option['text'] }}</span>
                                                    @endif
                                                    @if($option['isCorrect'])
                                                        <span class="badge bg-success ms-2">
                                                            <i class="fas fa-check me-1"></i>Benar
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($option['useImage'] && isset($option['imagePath']) && $option['imagePath'])
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $option['imagePath']) }}" 
                                                         alt="Gambar untuk pilihan {{ strtoupper($option['id']) }}" 
                                                         class="img-fluid rounded" style="max-height: 200px;">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Dibuat: {{ $question->created_at->format('d F Y') }}
                        </small>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>