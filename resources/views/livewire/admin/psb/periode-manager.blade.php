<section>
    @if (session()->has('success'))
        <div class="d-flex justify-content-end">
            <div wire:alive class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="d-flex justify-content-end">
            <div wire:alive class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Daftar Periode Ujian</h5>
            <div class="d-flex">
                <input type="text" wire:model.live.debounce.500ms="search" class="form-control me-2" placeholder="Cari periode...">
                <button wire:click='create' data-bs-toggle="modal" data-bs-target="#createOrUpdatePeriode"
                    class="btn btn-primary">Tambah Periode +</button>
            </div>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Periode</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                        <th>Tahun Ajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->listPeriods() as $periode)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $periode->nama_periode }}</td>
                            <td>{{ \Carbon\Carbon::parse($periode->periode_mulai)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($periode->periode_selesai)->format('d M Y') }}</td>
                            <td>{{ ucfirst($periode->status_periode) }}</td>
                            <td>{{ $periode->tahun_ajaran }}</td>
                            <td>
                                <button wire:click='edit("{{ $periode->id }}")' data-bs-toggle="modal"
                                    data-bs-target="#createOrUpdatePeriode"
                                    class="btn btn-warning btn-sm">Edit</button>
                                <button wire:click='deletePeriode("{{ $periode->id }}")'
                                    class="btn btn-danger btn-sm"
                                    wire:confirm='Apakah kamu ingin menghapus "{{ $periode->nama_periode }}"?'>Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada periode!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $this->listPeriods()->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" wire:ignore.self id="createOrUpdatePeriode" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form wire:submit.prevent='{{ $periodeId ? 'updatePeriode' : 'createPeriode' }}'>
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $periodeId ? 'Edit Periode' : 'Periode Baru' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Periode</label>
                            <input type="text" required class="form-control" wire:model.live="periodeForm.nama_periode">
                            @error('periodeForm.nama_periode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" required class="form-control" wire:model.live="periodeForm.periode_mulai">
                            @error('periodeForm.periode_mulai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" required class="form-control" wire:model.live="periodeForm.periode_selesai">
                            @error('periodeForm.periode_selesai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Periode</label>
                            <select class="form-control" wire:model.live="periodeForm.status_periode">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('periodeForm.status_periode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" required class="form-control" wire:model.live="periodeForm.tahun_ajaran" placeholder="Contoh: 2025/2026">
                            @error('periodeForm.tahun_ajaran')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit"
                            class="btn {{ $periodeId ? 'btn-warning' : 'btn-primary' }}">{{ $periodeId ? 'Update' : 'Tambah' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>