<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Periksa Ujian</h3>
                <p class="text-subtitle text-muted">
                    Periksa ujian {{ $ujian->mata_pelajaran }} - {{ $santri->nama_lengkap }}
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.master-ujian.hasil-ujian') }}">Hasil Ujian</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Periksa Ujian
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible show fade">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Informasi Ujian</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Santri</label>
                            <p class="font-bold">{{ $santri->nama_lengkap }}</p>
                        </div>
                        <div class="form-group">
                            <label>NISN</label>
                            <p class="font-bold">{{ $santri->nisn }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <p class="font-bold">{{ $ujian->mata_pelajaran }}</p>
                        </div>
                        <div class="form-group">
                            <label>Waktu Pengerjaan</label>
                            <p class="font-bold">{{ $hasilUjian->created_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Soal & Jawaban</h4>
                <div wire:poll.750ms>
                    <span class="badge bg-primary">Total Nilai: {{ $totalNilai }}</span>
                    <span class="badge bg-info ms-2">Rata-rata: {{ $rataRata }}</span>
                </div>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="simpanNilai">
                    @foreach($soals as $index => $soal)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5>Soal {{ $index + 1 }}</h5>
                                <div class="d-flex align-items-center">
                                    @if($soal->tipe_soal === 'pg')
                                        <span class="badge bg-secondary me-2">Poin Default: {{ $soal->poin }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="question-content mb-3">
                                {!! $soal->pertanyaan !!}
                            </div>

                            @if($soal->tipe_soal === 'pg')
                                <div class="options-list mb-3">
                                    <p class="mb-1"><strong>Pilihan Jawaban:</strong></p>
                                    <div class="options">
                                        @php $pilihanJawaban = $this->getPilihanJawaban($soal); @endphp
                                        @foreach($pilihanJawaban as $key => $pilihan)
                                            <div class="option {{ isset($jawabanUjian[$soal->id]) && $jawabanUjian[$soal->id]->jawaban == $key ? 'selected' : '' }}">
                                                {{ chr(65 + $key) }}. {!! $pilihan !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="answer mb-3">
                                    <p class="mb-1"><strong>Jawaban Santri:</strong></p>
                                    <p>{{ isset($jawabanUjian[$soal->id]) ? $this->getJawabanPG($soal, $jawabanUjian[$soal->id]->jawaban) : '-' }}</p>
                                </div>
                            @else
                                <div class="answer mb-3">
                                    <p class="mb-1"><strong>Jawaban Santri:</strong></p>
                                    <p>{!! isset($jawabanUjian[$soal->id]) ? nl2br(e($jawabanUjian[$soal->id]->jawaban)) : '-' !!}</p>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="nilai-{{ $soal->id }}">Nilai</label>
                                <input type="number" 
                                    id="nilai-{{ $soal->id }}"
                                    class="form-control @error('nilaiSoal.' . $soal->id) is-invalid @enderror"
                                    wire:model.live="nilaiSoal.{{ $soal->id }}"
                                    min="0"
                                    max="100">
                                @error('nilaiSoal.' . $soal->id)
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="komentar-{{ $soal->id }}">Komentar (Opsional)</label>
                                <textarea 
                                    id="komentar-{{ $soal->id }}"
                                    class="form-control"
                                    wire:model="komentar.{{ $soal->id }}"
                                    rows="2"></textarea>
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <a href="{{ route('admin.master-ujian.hasil-ujian') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Nilai
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Add any necessary JavaScript here
        });
    </script>
    @endpush
</div> 
 
 
 
 
 