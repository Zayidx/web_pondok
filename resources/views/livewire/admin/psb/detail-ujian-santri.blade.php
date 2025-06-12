<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Ujian Santri</h3>
                <p class="text-subtitle text-muted">Lihat detail ujian dan nilai santri.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.psb.ujian.hasil') }}">Hasil Ujian</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Ujian</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Santri</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ $santri->nama_lengkap }}</p>
                            <p><strong>NISN:</strong> {{ $santri->nisn }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $santri->status_santri === 'sedang_ujian' ? 'warning' : 'success' }}">
                                    {{ ucfirst(str_replace('_', ' ', $santri->status_santri)) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Nilai:</strong> {{ number_format($totalNilai, 2) }}</p>
                            <p><strong>Rata-rata Nilai:</strong> {{ number_format($rataRata, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Ujian</h4>
                    <div class="d-flex gap-2">
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Cari ujian...">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th wire:click="sortBy('nama_ujian')" style="cursor: pointer">
                                        Nama Ujian
                                        @if($sortField === 'nama_ujian')
                                            <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('mata_pelajaran')" style="cursor: pointer">
                                        Mata Pelajaran
                                        @if($sortField === 'mata_pelajaran')
                                            <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('tanggal_ujian')" style="cursor: pointer">
                                        Tanggal
                                        @if($sortField === 'tanggal_ujian')
                                            <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    <th>Status</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ujianList as $ujian)
                                    @php
                                        $hasilUjian = $ujian->hasilUjians->first();
                                        $status = $hasilUjian ? $hasilUjian->status : 'belum_mulai';
                                        $nilai = $hasilUjian ? $hasilUjian->nilai_akhir : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $ujian->nama_ujian }}</td>
                                        <td>{{ $ujian->mata_pelajaran }}</td>
                                        <td>{{ $ujian->tanggal_ujian->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $status === 'selesai' ? 'success' : 
                                                ($status === 'sedang_mengerjakan' ? 'warning' : 'secondary')
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($nilai, 2) }}</td>
                                        <td>
                                            @if($hasilUjian)
                                                <a href="{{ route('admin.psb.ujian.detail-soal', ['ujianId' => $ujian->id, 'santriId' => $santri->id]) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
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
</div>