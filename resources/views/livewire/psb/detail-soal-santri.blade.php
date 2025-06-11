<div>
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
                    <h5 class="card-title mb-1">Ujian: {{ $ujian->nama_ujian }}</h5>
                    <h6 class="card-subtitle text-muted">Santri: {{ $santri->nama_lengkap }}</h6>
                </div>
                <div class="col-md-6 d-flex flex-column flex-md-row justify-content-start justify-content-md-end align-items-md-center gap-3">
                    {{-- MENAMPILKAN NILAI RATA-RATA KESELURUHAN --}}
                    <h6 class="mb-0">
                        Rata-rata Semua Ujian: 
                        <span class="badge bg-info fs-6">{{ number_format($santri->rata_rata_ujian, 2) }}</span>
                    </h6>
                    <h5 class="mb-0">
                        Total Poin Ujian Ini: 
                        <span class="badge bg-primary fs-5">{{ number_format($totalPoin, 2) }}</span>
                    </h5>
                    <button wire:click="saveNilai" class="btn btn-success" wire:loading.attr="disabled">
                        <i class="bi bi-save"></i> 
                        <span wire:loading.remove wire:target="saveNilai">Simpan Nilai</span>
                        <span wire:loading wire:target="saveNilai">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    
    {{-- Daftar Soal dan Jawaban --}}
    <div class="row">
        <div class="col-12">
            @forelse($soalUjian as $index => $soal)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Soal #{{ $index + 1 }}</h5>
                        <span class="badge bg-{{$soal->tipe_soal == 'pg' ? 'info' : 'warning'}}">{{$soal->tipe_soal == 'pg' ? 'Pilihan Ganda' : 'Esai'}}</span>
                    </div>
                    <div class="card-body">
                        <div class="prose" style="max-width: none;">{!! $soal->pertanyaan !!}</div>
                        <hr>
                        
                        {{-- Tampilan untuk Soal Pilihan Ganda --}}
                        @if($soal->tipe_soal === 'pg')
                            @php
                                $jawabanSantri = $jawabanUjian->get($soal->id)?->jawaban;
                                $jawabanSantriHuruf = is_numeric($jawabanSantri) ? chr((int)$jawabanSantri + 65) : $jawabanSantri;
                            @endphp
                            <div class="row">
                                @foreach($soal->opsi as $key => $option)
                                    @php
                                        $letter = chr($key + 65);
                                        $isCorrect = ($letter === $soal->kunci_jawaban);
                                        $isSantriAnswer = ($letter === $jawabanSantriHuruf);
                                        
                                        $labelClass = '';
                                        if ($isCorrect) $labelClass = 'text-success fw-bold';
                                        elseif ($isSantriAnswer && !$isCorrect) $labelClass = 'text-danger';
                                    @endphp
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check p-2 rounded {{ $isSantriAnswer ? 'bg-light border' : '' }}">
                                            <input class="form-check-input" type="radio" disabled {{ $isSantriAnswer ? 'checked' : '' }}>
                                            <label class="form-check-label {{ $labelClass }}">
                                                {{ $letter }}. {{ is_array($option) ? $option['teks'] : $option }}
                                                @if($isCorrect) <i class="bi bi-check-circle-fill text-success ms-1" title="Kunci Jawaban"></i>@endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 p-2 bg-light rounded d-flex justify-content-between align-items-center">
                                <span>Jawaban Santri: <strong>{{ $jawabanSantriHuruf ?? 'Tidak Dijawab' }}</strong> | Kunci: <strong>{{ $soal->kunci_jawaban }}</strong></span>
                                @php
                                    $poinDidapat = ($jawabanSantriHuruf === $soal->kunci_jawaban) ? $soal->poin : 0;
                                @endphp
                                <span class="badge bg-{{ $poinDidapat > 0 ? 'success' : 'secondary' }}">
                                    Poin Diperoleh: {{ $poinDidapat }} / {{ $soal->poin }}
                                </span>
                            </div>

                        {{-- Tampilan untuk Soal Esai --}}
                        @else
                            <h6 class="mb-2">Jawaban Santri:</h6>
                            <div class="p-3 bg-light rounded border mb-3">
                                <p class="mb-0 fst-italic">"{{ $jawabanUjian->get($soal->id)?->jawaban ?? 'Tidak Dijawab' }}"</p>
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
                        <p class="text-muted">Tidak ada soal yang ditemukan untuk ujian ini.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>