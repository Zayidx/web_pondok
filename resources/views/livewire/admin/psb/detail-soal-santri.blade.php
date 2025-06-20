<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
               
                <p class="text-subtitle text-muted">Periksa jawaban dan berikan nilai.</p>
            </div>
            
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @forelse($soalUjian as $index => $soal)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar {{ $soal->tipe_soal == 'pg' ? 'bg-info' : 'bg-warning' }}">
                                <span class="avatar-content">#{{ $index + 1 }}</span>
                            </div>
                            <h5 class="mb-0">Soal {{ $index + 1 }}</h5>
                        </div>
                        <span class="badge bg-{{$soal->tipe_soal == 'pg' ? 'info' : 'warning'}}">
                            <i class="bi bi-{{ $soal->tipe_soal == 'pg' ? 'list-check' : 'pencil-square' }} me-1"></i>
                            {{$soal->tipe_soal == 'pg' ? 'Pilihan Ganda' : 'Esai'}}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="prose mb-4" style="max-width: none;">{!! $soal->pertanyaan !!}</div>
                        <hr>
                        
                        @if($soal->tipe_soal === 'pg')
                            @php
                                $jawabanSantri = $jawabanUjian->get($soal->id)?->jawaban;
                                $jawabanSantriHuruf = is_numeric($jawabanSantri) ? chr((int)$jawabanSantri + 65) : $jawabanSantri;
                            @endphp
                            <div class="row g-3">
                                @foreach($soal->opsi as $key => $option)
                                    @php
                                        $letter = chr($key + 65);
                                        $isCorrect = ($letter === $soal->kunci_jawaban);
                                        $isSantriAnswer = ($letter === $jawabanSantriHuruf);
                                        $bobot = $option['bobot'] ?? 0;
                                        
                                        $cardClass = 'bg-light';
                                        if ($isSantriAnswer && $isCorrect) {
                                            $cardClass = 'bg-success bg-opacity-10 border-success';
                                        } elseif ($isSantriAnswer && !$isCorrect) {
                                            $cardClass = 'bg-danger bg-opacity-10 border-danger';
                                        } elseif ($isCorrect) {
                                            $cardClass = 'bg-success bg-opacity-10';
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="card {{ $cardClass }} border">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="avatar {{ $isSantriAnswer ? ($isCorrect ? 'bg-success' : 'bg-danger') : 'bg-secondary' }}">
                                                        <span class="avatar-content">{{ $letter }}</span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <p class="mb-1">{{ is_array($option) ? $option['teks'] : $option }}</p>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-secondary">{{ $bobot }} poin</span>
                                                            @if($isCorrect)
                                                                <span class="badge bg-success">
                                                                    <i class="bi bi-check-circle-fill me-1"></i>
                                                                    Kunci Jawaban
                                                                </span>
                                                            @endif
                                                            @if($isSantriAnswer)
                                                                <span class="badge bg-primary">
                                                                    <i class="bi bi-person-check-fill me-1"></i>
                                                                    Jawaban Santri
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @php
                                $poinDidapat = 0;
                                if ($jawabanSantriHuruf) {
                                    $answerIndex = ord(strtoupper($jawabanSantriHuruf)) - 65;
                                    if (isset($soal->opsi[$answerIndex]['bobot'])) {
                                        $poinDidapat = (float)$soal->opsi[$answerIndex]['bobot'];
                                    }
                                }
                            @endphp
                            <div class="alert {{ $poinDidapat > 0 ? 'alert-success' : 'alert-secondary' }} mt-3 mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-info-circle me-2"></i>
                                        Poin diperoleh dari jawaban ini
                                    </span>
                                    <strong>{{ $poinDidapat }} poin</strong>
                                </div>
                            </div>
                        @else
                            <div class="card bg-light border mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3">
                                        <i class="bi bi-chat-left-text me-2"></i>
                                        Jawaban Santri
                                    </h6>
                                    <p class="mb-0 fst-italic">"{{ $jawabanUjian->get($soal->id)?->jawaban ?? 'Tidak Dijawab' }}"</p>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <label class="col-md-auto col-form-label fw-bold">Beri Nilai:</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="number" 
                                               wire:model.lazy="poinEssay.{{ $soal->id }}"
                                               class="form-control" 
                                               min="0" 
                                               max="{{ $soal->poin }}">
                                        <span class="input-group-text">/ {{ $soal->poin }} Poin</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center">
                        <div class="py-5">
                            <i class="bi bi-inbox text-muted display-1"></i>
                            <p class="text-muted mt-3">Tidak ada soal yang ditemukan untuk ujian ini.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="col-lg-4">
            <div class="sticky-top" style="top: 1rem;">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ $ujian->nama_ujian }}</h5>
                        <div class="d-flex align-items-center gap-3">
                        @php
        $fotoDokumen = $santri->dokumen->where('jenis_berkas', 'Pas Foto')->first();
        $fotoPath = $fotoDokumen ? asset('storage/' . $fotoDokumen->file_path) : 'URL_PATH_TO_DEFAULT_AVATAR';
        // Ganti 'URL_PATH_TO_DEFAULT_AVATAR' dengan path ke gambar default jika santri tidak memiliki foto
    @endphp
                            <div>
                                <h6 class="mb-0">{{ $santri->nama_lengkap }}</h6>
                                <small class="text-muted">Peserta Ujian</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                
                    <div class="card-body">
                       
                        

                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Total Poin Ujian Ini</h5>
                            <h4 class="mb-0 text-primary fw-bold">{{ number_format($totalPoin, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button wire:click="saveNilai" class="btn btn-success w-100" wire:loading.attr="disabled">
                            <i class="bi bi-save me-2"></i> 
                            <span wire:loading.remove wire:target="saveNilai">Simpan Seluruh Nilai</span>
                            <span wire:loading wire:target="saveNilai">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }
    .avatar-content {
        font-size: 1.2rem;
    }
    .prose {
        line-height: 1.6;
    }
    </style>
</div>