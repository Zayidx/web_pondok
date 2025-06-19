<div>
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
    @if (session()->has('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session()->has('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard Daftar Ulang</h3>
                <p class="text-subtitle text-muted">Manajemen verifikasi pendaftaran ulang santri baru.</p>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h4>Daftar Pendaftar Ulang</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4 g-2">
                <div class="col-md-9">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari berdasarkan nama atau NISN...">
                </div>
                <div class="col-md-3">
                    <button wire:click="resetFilters" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>NISN</th>
                            <th>Tanggal Update</th>
                            <th>Status Pembayaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                            <tr>
                                <td>{{ $registration->nama_lengkap }}</td>
                                <td>{{ $registration->nisn }}</td>
                                <td>{{ $registration->updated_at->format('d F Y H:i') }}</td>
                                <td>
                                    @if($registration->status_pembayaran === 'verified')
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @elseif($registration->status_pembayaran === 'rejected')
                                         <span class="badge bg-danger">Ditolak</span>
                                    @elseif($registration->status_pembayaran === 'pending')
                                        <span class="badge bg-info">Menunggu Verifikasi</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($registration->status_pembayaran) ?? 'Belum Bayar' }}</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <button wire:click="showDetail({{ $registration->id }})" class="btn btn-primary btn-sm" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    
                                    <button wire:click="editRegistration({{ $registration->id }})" class="btn btn-warning btn-sm" title="Edit Data">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    @if($registration->bukti_pembayaran)
                                        <button wire:click="viewPaymentProof({{ $registration->id }})" class="btn btn-info btn-sm" title="Lihat Bukti Pembayaran">
                                            <i class="bi bi-file-earmark-image"></i>
                                        </button>
                                        @if($registration->status_pembayaran === 'pending')
                                            <button wire:click="verifyRegistration({{ $registration->id }})" 
                                                    wire:confirm="Anda yakin ingin MEMVERIFIKASI pendaftaran ulang santri ini?"
                                                    class="btn btn-success btn-sm" title="Terima">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button wire:click="rejectRegistration({{ $registration->id }})" 
                                                    wire:confirm="Anda yakin ingin MENOLAK bukti pembayaran ini? Status akan diubah menjadi 'ditolak'."
                                                    class="btn btn-danger btn-sm" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data untuk ditampilkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>

    @if($showDetailModal && $selectedRegistration)
        <div class="modal fade show" tabindex="-1" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Pendaftar Ulang</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Data Santri</h6>
                                <table class="table table-borderless table-sm">
                                    <tr><td class="fw-bold">Nama Lengkap</td><td>: {{ $selectedRegistration->nama_lengkap }}</td></tr>
                                    <tr><td class="fw-bold">NISN</td><td>: {{ $selectedRegistration->nisn }}</td></tr>
                                    <tr><td class="fw-bold">Asal Sekolah</td><td>: {{ $selectedRegistration->asal_sekolah }}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Data Pembayaran</h6>
                                <table class="table table-borderless table-sm">
                                    <tr><td class="fw-bold">Bank Pengirim</td><td>: {{ $selectedRegistration->bank_pengirim ?? '-' }}</td></tr>
                                    <tr><td class="fw-bold">Nama Pengirim</td><td>: {{ $selectedRegistration->nama_pengirim ?? '-' }}</td></tr>
                                    <tr><td class="fw-bold">Tgl. Pembayaran</td><td>: {{ optional($selectedRegistration->tanggal_pembayaran)->format('d F Y') ?? '-' }}</td></tr>
                                    <tr><td class="fw-bold">Status</td><td>: 
                                        @if($selectedRegistration->status_pembayaran === 'verified') <span class="badge bg-success">Terverifikasi</span>
                                        @elseif($selectedRegistration->status_pembayaran === 'rejected') <span class="badge bg-danger">Ditolak</span>
                                        @else <span class="badge bg-info">Menunggu Verifikasi</span> @endif
                                    </td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if($showProofModal)
        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Bukti Pembayaran</h5><button type="button" class="btn-close" wire:click="closeProofModal"></button></div>
                    <div class="modal-body text-center"><img src="{{ $proofImageUrl }}" class="img-fluid rounded" alt="Bukti Pembayaran"></div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if($showEditModal && $selectedRegistration)
        <div class="modal fade show" tabindex="-1" style="display: block;" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Pembayaran: {{ $selectedRegistration->nama_lengkap }}</h5>
                        <button type="button" class="btn-close" wire:click="closeEditModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <h6>Data Santri</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Lengkap</label>
                                    <p class="form-control-plaintext ps-2">{{ $selectedRegistration->nama_lengkap }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">NISN</label>
                                    <p class="form-control-plaintext ps-2">{{ $selectedRegistration->nisn }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Asal Sekolah</label>
                                    <p class="form-control-plaintext ps-2">{{ $selectedRegistration->asal_sekolah }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6>Data Pembayaran (Edit)</h6>
                                <div class="mb-3">
                                    <label for="edit_bank_pengirim" class="form-label">Bank Pengirim</label>
                                    <input type="text" id="edit_bank_pengirim" class="form-control @error('editForm.bank_pengirim') is-invalid @enderror" wire:model="editForm.bank_pengirim">
                                    @error('editForm.bank_pengirim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="edit_nama_pengirim" class="form-label">Nama Pengirim</label>
                                    <input type="text" id="edit_nama_pengirim" class="form-control @error('editForm.nama_pengirim') is-invalid @enderror" wire:model="editForm.nama_pengirim">
                                    @error('editForm.nama_pengirim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="edit_tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                                    <input type="date" id="edit_tanggal_pembayaran" class="form-control @error('editForm.tanggal_pembayaran') is-invalid @enderror" wire:model="editForm.tanggal_pembayaran">
                                    @error('editForm.tanggal_pembayaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6>Bukti Pembayaran</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="new_proof_image" class="form-label">Ganti Bukti Pembayaran (Opsional)</label>
                                <input type="file" id="new_proof_image" class="form-control @error('newProofImage') is-invalid @enderror" wire:model="newProofImage">
                                <div wire:loading wire:target="newProofImage" class="text-primary mt-1">Mengunggah...</div>
                                @error('newProofImage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 fw-bold">Bukti Saat Ini:</p>
                                @if($selectedRegistration->bukti_pembayaran)
                                    <a href="{{ Storage::url($selectedRegistration->bukti_pembayaran) }}" target="_blank">
                                        <img src="{{ Storage::url($selectedRegistration->bukti_pembayaran) }}" class="img-thumbnail" style="max-height: 150px;" alt="Bukti Pembayaran Saat Ini">
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada bukti pembayaran.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeEditModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updateRegistration" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="updateRegistration">Simpan Perubahan</span>
                            <span wire:loading wire:target="updateRegistration">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>