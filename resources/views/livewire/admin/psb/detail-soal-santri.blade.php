<div class="container-fluid">
    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Judul Halaman --}}
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Penilaian Ujian Santri</h3>
                <p class="text-subtitle text-muted">Periksa jawaban dan berikan nilai.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.psb.ujian.hasil') }}" wire:navigate>Hasil Ujian</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.psb.ujian.detail', ['id' => $santriId]) }}" wire:navigate>Detail Santri</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Penilaian</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Kartu Informasi & Aksi --}}
    <div class="card sticky-top" style="top: 1rem; z-index: 100;">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-md-6">
                    <h5 class="card-title mb-3">{{ $ujian->nama_ujian }}</h5>
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar bg-primary">
                            <span class="avatar-content">{{ substr($santri->nama_lengkap, 0, 1) }}</span>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $santri->nama_lengkap }}</h6>
                            <small class="text-muted">Peserta Ujian</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row g-3">
                        {{-- Nilai Rata-rata --}}
                        <div class="col-md-6">
                            <div class="card bg-light mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-2">Rata-rata Semua Ujian</h6>
                                            <h4 class="mb-0">{{ number_format($santri->rata_rata_ujian, 2) }}</h4>
                                        </div>
                                        <div class="avatar bg-info">
                                            <i class="bi bi-graph-up"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Total Poin --}}
                        <div class="col-md-6">
                            <div class="card bg-primary text-white mb-0">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-2">Total Poin Ujian Ini</h6>
                                            <h4 class="mb-0">{{ number_format($totalPoin, 2) }}</h4>
                                        </div>
                                        <div class="avatar bg-white text-primary">
                                            <i class="bi bi-trophy"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Tombol Simpan --}}
                    <div class="d-flex justify-content-end mt-3">
                    <button wire:click="saveNilai" class="btn btn-success" wire:loading.attr="disabled">
                        <i class="bi bi-save"></i> 
                        <span wire:loading.remove wire:target="saveNilai">Simpan Nilai</span>
                        <span wire:loading wire:target="saveNilai">Menyimpan...</span>
                    </button>
                </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Nilai --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Ringkasan Nilai</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @php
                    $totalPG = 0;
                    $totalEssay = 0;
                    $jumlahSoalPG = 0;
                    $jumlahSoalEssay = 0;

                    // Hitung total poin PG dan Essay
                    foreach($soalUjian as $soal) {
                        if($soal->tipe_soal === 'pg') {
                            $jumlahSoalPG++;
                            $jawaban = $jawabanUjian->get($soal->id);
                            if ($jawaban && $jawaban->jawaban) {
                                $answerIndex = ord(strtoupper($jawaban->jawaban)) - 65;
                                if (isset($soal->opsi[$answerIndex]['bobot'])) {
                                    $totalPG += (float)$soal->opsi[$answerIndex]['bobot'];
                                }
                            }
                        } else {
                            $jumlahSoalEssay++;
                            if (isset($poinEssay[$soal->id])) {
                                $totalEssay += (float)$poinEssay[$soal->id];
                            }
                        }
                    }

                    // Update wire:model totalPoin
                    $this->totalPoin = $totalPG + $totalEssay;
                @endphp

                <div class="col-md-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar bg-primary me-3">
                                    <i class="bi bi-list-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Pilihan Ganda</h6>
                                    <small class="text-muted">{{ $jumlahSoalPG }} Soal</small>
                                </div>
                            </div>
                            <h3 class="mb-0">{{ number_format($totalPG, 2) }} <small class="text-muted">poin</small></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar bg-warning me-3">
                                    <i class="bi bi-pencil-square"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Esai</h6>
                                    <small class="text-muted">{{ $jumlahSoalEssay }} Soal</small>
                                </div>
                            </div>
                            <h3 class="mb-0">{{ number_format($totalEssay, 2) }} <small class="text-muted">poin</small></h3>
            </div>
        </div>
    </div>
    
                <div class="col-md-4">
                    <div class="card border-0 bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar bg-white text-primary me-3">
                                    <i class="bi bi-calculator"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Total Nilai</h6>
                                    <small class="text-white-50">Semua Tipe Soal</small>
                                </div>
                            </div>
                            <h3 class="mb-0" wire:model="totalPoin">{{ number_format($totalPG + $totalEssay, 2) }} <small class="text-white-50">poin</small></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Daftar Soal dan Jawaban --}}
    <div class="row">
        <div class="col-12">
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
                        
                        {{-- Tampilan untuk Soal Pilihan Ganda --}}
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

                        {{-- Tampilan untuk Soal Esai --}}
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
                                <div class="col-md-3">
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
    .card-shadow {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }
    .prose {
        line-height: 1.6;
    }
    </style>
</div>