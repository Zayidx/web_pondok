<div>
    {{-- Menampilkan notifikasi sukses/error --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3000ms>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif
    </div>

    {{-- Judul Halaman --}}
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Hasil Ujian Santri</h3>
                <p class="text-subtitle text-muted">Daftar santri yang sedang mengikuti ujian.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Santri</h4>
                    <div class="d-flex gap-2">
                        <button wire:click="resetFilters" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" wire:model.live="search" class="form-control" placeholder="Cari nama/NISN/email...">
                        </div>
                        <div class="col-md-3">
                            <input type="text" wire:model.live="searchAlamat" class="form-control" placeholder="Cari alamat...">
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filters.tipe_pendaftaran" class="form-select">
                                @foreach($tipeOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th wire:click="sortBy('nama_lengkap')" style="cursor: pointer">
                                        Nama Santri
                                        @if($sortField === 'nama_lengkap')
                                            <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    <th>NISN</th>
                                    <th>Email</th>
                                    <th>Tipe Pendaftaran</th>
                                    <th wire:click="sortBy('total_nilai_semua_ujian')" style="cursor: pointer">
                                        Total Nilai
                                        @if($sortField === 'total_nilai_semua_ujian')
                                            <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    <th wire:click="sortBy('rata_rata_ujian')" style="cursor: pointer">
                                        Rata-rata
                                        @if($sortField === 'rata_rata_ujian')
                                            <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                        @endif
                                    </th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($santriList as $santri)
                                    <tr>
                                        <td>{{ $santri->nama_lengkap }}</td>
                                        <td>{{ $santri->nisn }}</td>
                                        <td>{{ $santri->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $santri->tipe_pendaftaran === 'reguler' ? 'primary' : 
                                                ($santri->tipe_pendaftaran === 'olimpiade' ? 'success' : 'warning')
                                            }}">
                                                {{ ucfirst($santri->tipe_pendaftaran) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($santri->total_nilai_semua_ujian, 2) }}</td>
                                        <td>{{ number_format($santri->rata_rata_ujian, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.psb.ujian.detail', ['id' => $santri->id]) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data santri</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $santriList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
