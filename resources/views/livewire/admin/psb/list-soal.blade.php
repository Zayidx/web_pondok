<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Daftar Soal</h3>
                <p class="text-subtitle text-muted">
                    Daftar soal untuk ujian {{ $ujian->mata_pelajaran }}
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.master-ujian.dashboard') }}">List Ujian</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Daftar Soal</li>
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
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        Daftar Soal
                    </h5>
                    <div class="d-flex gap-3">
                        <div class="search-box">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari soal...">
                        </div>
                        <select wire:model.live="perPage" class="form-select w-auto">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <button class="btn btn-primary" wire:click="createSoal">
                            <i class="bi bi-plus"></i> Tambah Soal
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th wire:click="sortBy('soal')" style="cursor: pointer;">
                                    Soal
                                    @if ($sortField === 'soal')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Jenis Soal</th>
                                <th>Bobot</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($soals as $index => $soal)
                                <tr>
                                    <td>{{ $soals->firstItem() + $index }}</td>
                                    <td>{!! Str::limit($soal->soal, 100) !!}</td>
                                    <td>
                                        <span class="badge bg-{{ $soal->jenis_soal === 'essay' ? 'primary' : 'info' }}">
                                            {{ str_replace('_', ' ', ucfirst($soal->jenis_soal)) }}
                                        </span>
                                    </td>
                                    <td>{{ $soal->bobot }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button wire:click="editSoal({{ $soal->id }})" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button wire:click="deleteSoal({{ $soal->id }})" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data soal</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $soals->links() }}
                </div>
            </div>
        </div>
    </section>

    <!-- Form Modal -->
    @if($showForm)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $soalId ? 'Edit Soal' : 'Tambah Soal' }}</h5>
                    <button type="button" class="btn-close" wire:click="resetForm"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="saveSoal">
                        <div class="mb-3">
                            <label class="form-label">Soal</label>
                            <textarea wire:model="soalText" class="form-control @error('soalText') is-invalid @enderror" 
                                      rows="4" placeholder="Masukkan soal..."></textarea>
                            @error('soalText')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Soal</label>
                                    <select wire:model="jenisSoal" class="form-select @error('jenisSoal') is-invalid @enderror">
                                        <option value="essay">Essay</option>
                                        <option value="pilihan_ganda">Pilihan Ganda</option>
                                    </select>
                                    @error('jenisSoal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Bobot</label>
                                    <input type="number" wire:model="bobot" 
                                           class="form-control @error('bobot') is-invalid @enderror"
                                           min="1" max="100">
                                    @error('bobot')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="resetForm">Batal</button>
                    <button type="button" class="btn btn-primary" wire:click="saveSoal">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div> 
 
 
 
 
 