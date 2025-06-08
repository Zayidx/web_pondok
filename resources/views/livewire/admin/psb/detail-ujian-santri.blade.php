<div>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Ujian Santri - {{ $santri->nama_lengkap }}</h1>
            <a href="{{ route('admin.psb.ujian.hasil') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Informasi Santri -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Santri</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150">Nama Lengkap</td>
                                <td>: {{ $santri->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td>NISN</td>
                                <td>: {{ $santri->nisn }}</td>
                            </tr>
                            <tr>
                                <td>Asal Sekolah</td>
                                <td>: {{ $santri->asal_sekolah }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150">Jenis Kelamin</td>
                                <td>: {{ $santri->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:   <span class="badge bg-primary">Sedang Ujian</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Ujian -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Ujian</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mata Pelajaran</th>
                                <th>Tanggal Ujian</th>
                                <th>Status</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ujianList as $index => $ujian)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $ujian->mata_pelajaran }}</td>
                                    <td>{{ $ujian->tanggal_ujian->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $hasilUjian = $ujian->hasilUjians->first();
                                            $status = $hasilUjian ? $hasilUjian->status : 'belum_mulai';
                                        @endphp
                                        <span class="badge bg-{{ 
                                            $status === 'belum_mulai' ? 'secondary' : 
                                            ($status === 'sedang_mengerjakan' ? 'warning' : 'success') 
                                        }}">
                                            {{ str_replace('_', ' ', ucfirst($status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($hasilUjian && $status === 'selesai')
                                            {{ $totalNilaiPerUjian[$ujian->id] ?? 'Belum dinilai' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasilUjian && $status === 'selesai')
                                            <button wire:click="viewSoal({{ $ujian->id }})" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat Soal
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-eye"></i> Lihat Soal
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data ujian</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detail Soal dan Jawaban -->
        @if($selectedUjian)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Detail Soal dan Jawaban - {{ $selectedUjian->mata_pelajaran }}
                </h6>
            </div>
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @foreach($selectedUjian->soals as $index => $soal)
                    <div class="soal-item mb-4 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="mb-3">Soal {{ $index + 1 }}</h6>
                            <span class="badge bg-info">{{ ucfirst($soal->tipe_soal) }}</span>
                        </div>
                        
                        <div class="pertanyaan mb-3">
                            {!! $soal->pertanyaan !!}
                        </div>

                        @if($soal->tipe_soal === 'pg')
                            <div class="pilihan-jawaban mb-3">
                                <strong>Pilihan Jawaban:</strong><br>
                                @if(is_array($soal->opsi) || is_object($soal->opsi))
                                    @foreach($soal->opsi as $key => $opsi)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" disabled
                                                {{ isset($jawabanUjian[$soal->id]) && $jawabanUjian[$soal->id]['jawaban'] == $key ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                {{ $opsi['teks'] ?? 'Opsi tidak valid' }}
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-danger">Opsi soal tidak valid.</div>
                                @endif
                            </div>
                            <div class="kunci-jawaban mb-3">
                                <strong>Kunci Jawaban:</strong> Opsi {{ $soal->kunci_jawaban }}
                            </div>
                            <div class="nilai">
                                <strong>Nilai:</strong>
                                @if(isset($jawabanUjian[$soal->id]))
                                    @if($jawabanUjian[$soal->id]['jawaban'] == $soal->kunci_jawaban)
                                        <span class="text-success">{{ $soal->poin }} (Benar)</span>
                                    @else
                                        <span class="text-danger">0 (Salah)</span>
                                    @endif
                                @else
                                    <span class="text-warning">Belum dijawab</span>
                                @endif
                            </div>
                        @else
                            <div class="jawaban mb-3">
                                <strong>Jawaban Siswa:</strong><br>
                                <div class="border p-2 rounded bg-light">
                                    {!! isset($jawabanUjian[$soal->id]) ? nl2br($jawabanUjian[$soal->id]['jawaban']) : 'Tidak ada jawaban' !!}
                                </div>
                            </div>
                            <div class="penilaian">
                                <strong>Nilai (Maks. {{ $soal->poin }}):</strong>
                                <input type="number" class="form-control w-auto d-inline-block ms-2 mb-2" 
                                    min="0" max="{{ $soal->poin }}"
                                    wire:model="nilaiEssay.{{ $soal->id }}"
                                    wire:change="hitungTotalNilai({{ $selectedUjian->id }})"
                                    value="{{ isset($jawabanUjian[$soal->id]) ? $jawabanUjian[$soal->id]['nilai'] : 0 }}">
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="total-nilai mt-4 d-flex align-items-center">
                    <h5 class="me-3">Total Nilai: {{ $totalNilai }}</h5>
                    <button type="button" wire:click="perbaruiSemuaNilai" class="btn btn-success btn-sm">
                        <i class="fas fa-save"></i> Perbarui Nilai
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>