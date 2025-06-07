<div>
    <div class="container-fluid">
        
        <!-- Search and Filter -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari nama atau NISN..." wire:model.live="search">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="text" wire:model.live="searchAlamat" class="form-control" placeholder="Cari alamat...">
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="filters.tipe" class="form-select">
                            <option value="">Semua Program</option>
                            @foreach($tipeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="filters.nilai" class="form-select">
                            <option value="">Urutan Nilai</option>
                            <option value="highest">Nilai Tertinggi</option>
                            <option value="lowest">Nilai Terendah</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <select wire:model.live="perPage" class="form-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button wire:click="resetFilters" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('nama_lengkap')" style="cursor: pointer;">
                                    Nama Lengkap
                                    @if ($sortField === 'nama_lengkap')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>NISN</th>
                                <th>Asal Sekolah</th>
                                <th>Program</th>
                                <th>Total Nilai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santriList as $santri)
                                <tr>
                                    <td>{{ $santri->nama_lengkap }}</td>
                                    <td>{{ $santri->nisn }}</td>
                                    <td>{{ $santri->asal_sekolah }}</td>
                                    <td>{{ $tipeOptions[$santri->tipe_pendaftaran] ?? '-' }}</td>
                                    <td>{{ number_format($this->getTotalNilai($santri), 2) }}</td>
                                    <td>
                                        <span class="badge bg-warning">Sedang Ujian</span>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.psb.ujian.detail', $santri->id) }}" 
                                               class="btn btn-sm btn-primary me-1">
                                                <i class="bi bi-eye"></i> Detail Ujian
                                            </a>
                                            <button wire:click="terimaSantri({{ $santri->id }})" 
                                                    class="btn btn-sm btn-success me-1">
                                                <i class="bi bi-check"></i> Terima
                                            </button>
                                            <button wire:click="tolakSantri({{ $santri->id }})" 
                                                    class="btn btn-sm btn-danger me-1">
                                                <i class="bi bi-x"></i> Tolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data santri yang sedang ujian</td>
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

    <!-- Alert Messages -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div> 
 
 
 
 
 