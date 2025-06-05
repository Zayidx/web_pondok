    <section>
        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4>Daftar Ujian</h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <input type="text" wire:model.live.debounce.500ms="search" class="form-control" placeholder="Cari nama ujian...">
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="sortMataPelajaran" class="form-select">
                            <option value="">Sort Mata Pelajaran</option>
                            <option value="asc">Mata Pelajaran (A-Z)</option>
                            <option value="desc">Mata Pelajaran (Z-A)</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <input type="date" wire:model.live="filterTanggal" class="form-control" placeholder="Filter Tanggal">
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="sortStatus" class="form-select">
                            <option value="">Filter Status</option>
                            <option value="draft">Draft</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <select wire:model="perPage" class="form-select">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button wire:click="create" data-bs-toggle="modal" data-bs-target="#createUjianModal" class="btn btn-primary">
                                <i class="bi bi-plus"> Tambah Ujian</i>
                            </button>
                            <button wire:click="resetFilters" class="btn btn-secondary">
                                <i class="bi bi-x-circle"> Reset Filter</i>
                            </button>
                        </div>
                    </div>
                </div>

                

                <div class="table-responsive">
                    <table class="table table-hover">
                    <thead>
                        <tr>
                                <th wire:click="sortBy('id')" style="cursor: pointer;">
                                    No
                                    @if ($sortField === 'id')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('nama_ujian')" style="cursor: pointer;">
                                    Nama Ujian
                                    @if ($sortField === 'nama_ujian')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('mata_pelajaran')" style="cursor: pointer;">
                                    Mata Pelajaran
                                    @if ($sortField === 'mata_pelajaran')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('tanggal_ujian')" style="cursor: pointer;">
                                    Tanggal
                                    @if ($sortField === 'tanggal_ujian')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                            <th>Waktu</th>
                                <th wire:click="sortBy('status_ujian')" style="cursor: pointer;">
                                    Status
                                    @if ($sortField === 'status_ujian')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                               @endif
                                </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->listUjian() as $ujian)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ujian->nama_ujian }}</td>
                                <td>{{ $ujian->mata_pelajaran }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d F Y') }}</td>
                                <td>{{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</td>
                                    <td>
                                        <span class="badge bg-{{ $ujian->status_ujian === 'aktif' ? 'success' : ($ujian->status_ujian === 'draft' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($ujian->status_ujian) }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('admin.master-ujian.detail', $ujian->id) }}" class="btn btn-info btn-sm me-1">
                                            <i class="bi bi-journal-text"></i> Soal
                                        </a>
                                        <button wire:click="edit({{ $ujian->id }})" data-bs-toggle="modal" data-bs-target="#createUjianModal" class="btn btn-warning btn-sm me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button wire:click="deleteUjian({{ $ujian->id }})" class="btn btn-danger btn-sm" wire:confirm="Apakah kamu ingin menghapus '{{ $ujian->nama_ujian }}'?">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada ujian!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <div class="mt-4">
                    {{ $this->listUjian()->links() }}
                </div>
            </div>
        </div>

        <div class="modal fade" wire:ignore.self id="createUjianModal" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form wire:submit.prevent="{{ $ujianId ? 'updateUjian' : 'createUjian' }}">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $ujianId ? 'Edit Ujian' : 'Ujian Baru' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Ujian</label>
                                <input type="text" class="form-control" wire:model.live="ujianForm.nama_ujian">
                                @error('ujianForm.nama_ujian') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <input type="text" class="form-control" wire:model.live="ujianForm.mata_pelajaran">
                                @error('ujianForm.mata_pelajaran') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Periode</label>
                                <select class="form-control" wire:model.live="ujianForm.periode_id">
                                    <option value="">Pilih Periode</option>
                                    @foreach (\App\Models\PSB\Periode::all() as $periode)
                                        <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                    @endforeach
                                </select>
                                @error('ujianForm.periode_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Ujian</label>
                                <input type="date" class="form-control" wire:model.live="ujianForm.tanggal_ujian">
                                @error('ujianForm.tanggal_ujian') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" wire:model.live="ujianForm.waktu_mulai">
                                @error('ujianForm.waktu_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" wire:model.live="ujianForm.waktu_selesai">
                                @error('ujianForm.waktu_selesai') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status Ujian</label>
                                <select class="form-control" wire:model.live="ujianForm.status_ujian">
                                    <option value="draft">Draft</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                                @error('ujianForm.status_ujian') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn {{ $ujianId ? 'btn-warning' : 'btn-primary' }}">{{ $ujianId ? 'Update' : 'Tambah' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>