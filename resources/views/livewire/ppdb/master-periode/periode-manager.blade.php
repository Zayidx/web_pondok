{{-- 
    Tampilan Manajemen Periode
    Menampilkan daftar periode dalam bentuk card
    Fitur: Edit periode
--}}
<section>
    {{-- Notifikasi Sukses --}}
    @if (session()->has('success'))
        <div class="d-flex justify-content-end">
            <div wire:alive class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Notifikasi Error --}}
    @if (session()->has('error'))
        <div class="d-flex justify-content-end">
            <div wire:alive class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Daftar Periode dalam Card --}}
    <div class="row">
        @forelse($periodeDaftarUlang as $periode)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card">
                    {{-- Header Card: Nama Periode --}}
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ $periode->nama_periode }}</h5>
                    </div>
                    {{-- Body Card: Informasi Periode --}}
                    <div class="card-body">
                        {{-- Tahun Ajaran --}}
                        <div class="mb-3">
                            <small class="text-muted">Tahun Ajaran</small>
                            <p class="mb-0">{{ $periode->tahun_ajaran }}</p>
                        </div>
                        {{-- Tanggal Mulai --}}
                        <div class="mb-3">
                            <small class="text-muted">Tanggal Mulai</small>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($periode->periode_mulai)->format('d M Y') }}</p>
                        </div>
                        {{-- Tanggal Selesai --}}
                        <div class="mb-3">
                            <small class="text-muted">Tanggal Selesai</small>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($periode->periode_selesai)->format('d M Y') }}</p>
                        </div>
                        {{-- Status Periode --}}
                        <div class="mb-3">
                            <small class="text-muted">Status</small>
                            <p class="mb-0">
                                <span class="badge bg-{{ $periode->status_periode === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($periode->status_periode) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    {{-- Footer Card: Tombol Edit --}}
                    <div class="card-footer">
                        <button wire:click='edit("{{ $periode->id }}")' data-bs-toggle="modal"
                            data-bs-target="#editPeriode" class="btn btn-warning btn-sm w-100">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        @empty
            {{-- Tampilan Kosong --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox text-muted display-4"></i>
                        <p class="text-muted mt-3">Tidak ada data periode</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Modal Edit Periode --}}
    <div class="modal fade" wire:ignore.self id="editPeriode" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form wire:submit.prevent='updatePeriode'>
                    {{-- Header Modal --}}
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Periode</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    {{-- Body Modal: Form Edit --}}
                    <div class="modal-body">
                        {{-- Input Nama Periode --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Periode</label>
                            <input type="text" required class="form-control" wire:model.live="periodeForm.nama_periode">
                            @error('periodeForm.nama_periode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Input Tanggal Mulai --}}
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" required class="form-control" wire:model.live="periodeForm.periode_mulai">
                            @error('periodeForm.periode_mulai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Input Tanggal Selesai --}}
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" required class="form-control" wire:model.live="periodeForm.periode_selesai">
                            @error('periodeForm.periode_selesai')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- Input Status Periode --}}
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
                        {{-- Input Tahun Ajaran --}}
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <input type="text" required class="form-control" wire:model.live="periodeForm.tahun_ajaran" placeholder="Contoh: 2025/2026">
                            @error('periodeForm.tahun_ajaran')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    {{-- Footer Modal: Tombol Aksi --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Style CSS --}}
    <style>
        /* Efek hover pada card */
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        /* Style untuk badge status */
        .badge {
            padding: 0.5em 0.75em;
        }
    </style>
</section> 