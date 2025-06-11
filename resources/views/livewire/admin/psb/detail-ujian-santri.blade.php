<div>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            
            <a href="{{ route('e-ppdb.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali Ke Dashboard Ujian
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
                                   {{-- resources/views/livewire/admin/psb/detail-ujian-santri.blade.php --}}

<td class="text-center">
    {{-- Kode ini memeriksa apakah status ujian sudah 'selesai' --}}
    @if($hasilUjian && $status === 'selesai')

        {{-- Jika ya, maka ia akan menampilkan nilai dari array
             yang sudah kita siapkan di komponen. --}}
        {{ $totalNilaiPerUjian[$ujian->id] ?? 'Belum dinilai' }}

    @else
        {{-- Jika belum selesai, tampilkan strip (-) --}}
        -
    @endif
</td>
                                    <td>
                                        @if($hasilUjian && $status === 'selesai')
                                            <a href="{{ route('admin.psb.ujian.detail-soal', ['ujianId' => $ujian->id, 'santriId' => $santri->id]) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Lihat Soal
                                            </a>
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
    </div>
</div>