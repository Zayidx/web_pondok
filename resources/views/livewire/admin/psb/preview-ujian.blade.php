<div class="container-fluid">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-1">{{ $ujian->nama_ujian }}</h3>
                            <p class="text-muted mb-0">{{ $ujian->mata_pelajaran }}</p>
                        </div>
                        <a href="{{ route('admin.master-ujian.detail', $ujian->id) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Detail Ujian
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Section --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Ujian</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Tanggal</td>
                                <td>:</td>
                                <td>{{ $ujian->tanggal_ujian->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Waktu</td>
                                <td>:</td>
                                <td>{{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Durasi</td>
                                <td>:</td>
                                <td>{{ $ujian->durasi }} menit</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Jumlah Soal</td>
                                <td>:</td>
                                <td>{{ $ujian->soals->count() }} soal</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Petunjuk Pengerjaan</h5>
                    <ol class="ps-3">
                        <li>Baca bismillah sebelum mengerjakan</li>
                        <li>Kerjakan soal sesuai dengan waktu yang ditentukan</li>
                        <li>Untuk soal pilihan ganda, pilih satu jawaban yang paling tepat</li>
                        <li>Untuk soal essay, jawab dengan jelas dan lengkap</li>
                        <li>Pastikan semua soal terjawab sebelum mengirim jawaban</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Questions Section --}}
    <div class="row">
        <div class="col-12">
            @foreach($ujian->soals as $index => $soal)
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <span class="fw-bold">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge {{ $soal->tipe_soal === 'pg' ? 'bg-info' : 'bg-warning' }}">
                                        {{ $soal->tipe_soal === 'pg' ? 'Pilihan Ganda' : 'Essay' }}
                                    </span>
                                </div>
                                <p class="mb-4">{{ $soal->pertanyaan }}</p>

                                @if($soal->tipe_soal === 'pg')
                                    <div class="list-group">
                                        @foreach($soal->opsi as $opsiIndex => $opsi)
                                            <label class="list-group-item list-group-item-action">
                                                <div class="d-flex align-items-center">
                                                    <input class="form-check-input me-3" type="radio" name="soal_{{ $soal->id }}" disabled>
                                                    <span>{{ chr(65 + $opsiIndex) }}. {{ $opsi['teks'] }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="form-group">
                                        <textarea class="form-control" rows="3" placeholder="Tulis jawaban essay di sini..." disabled></textarea>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div> 