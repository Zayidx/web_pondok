<div>
    {{-- Notifikasi --}}
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Judul Halaman --}}
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Dashboard Daftar Ulang</h3>
                <p class="text-subtitle text-muted">Manajemen verifikasi pendaftaran ulang santri baru.</p>
            </div>
        </div>
    </div>
    
    {{-- Kartu Utama --}}
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
                            <th>Tanggal Daftar</th>
                            <th>Status Pembayaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                            <tr>
                                <td>{{ $registration->nama_lengkap }}</td>
                                <td>{{ $registration->nisn }}</td>
                                <td>{{ $registration->created_at->format('d F Y') }}</td>
                                <td>
                                    @if(!$registration->bukti_pembayaran)
                                        <span class="badge bg-warning">Menunggu Bukti</span>
                                    @elseif($registration->status_pembayaran === 'verified')
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @elseif($registration->status_pembayaran === 'rejected')
                                         <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-info">Menunggu Verifikasi</span>
                                    @endif
                                </td>
                                <td class="text-center text-nowrap">
                                    <button wire:click="showDetail({{ $registration->id }})" class="btn btn-primary btn-sm" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @if($registration->bukti_pembayaran)
                                        <button wire:click="viewPaymentProof({{ $registration->id }})" class="btn btn-info btn-sm" title="Lihat Bukti Pembayaran">
                                            <i class="bi bi-file-earmark-image"></i>
                                        </button>
                                        @if($registration->status_pembayaran === 'pending' || $registration->status_pembayaran === null)
                                            <button wire:click="verifyRegistration({{ $registration->id }})" 
                                                    wire:confirm="Anda yakin ingin memverifikasi pendaftaran ulang santri ini?"
                                                    class="btn btn-success btn-sm" title="Terima">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button wire:click="rejectRegistration({{ $registration->id }})" 
                                                    wire:confirm="Anda yakin ingin menolak bukti pembayaran ini? Santri harus mengupload ulang."
                                                    class="btn btn-danger btn-sm" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data pendaftaran ulang.</td>
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
                                    <tr>
                                        <td class="fw-bold">Nama Lengkap</td>
                                        <td>: {{ $selectedRegistration->nama_lengkap }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">NISN</td>
                                        <td>: {{ $selectedRegistration->nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Asal Sekolah</td>
                                        <td>: {{ $selectedRegistration->asal_sekolah }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Data Pembayaran</h6>
                                <table class="table table-borderless table-sm">
                                    @if($selectedRegistration->bukti_pembayaran)
                                        <tr>
                                            <td class="fw-bold">Bank Pengirim</td>
                                            <td>: {{ $selectedRegistration->bank_pengirim }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nama Pengirim</td>
                                            <td>: {{ $selectedRegistration->nama_pengirim }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tgl. Pembayaran</td>
                                            <td>: {{ optional($selectedRegistration->tanggal_pembayaran)->format('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status</td>
                                            <td>: 
                                                @if($selectedRegistration->status_pembayaran === 'verified')
                                                    <span class="badge bg-success">Terverifikasi</span>
                                                @else
                                                    <span class="badge bg-info">Menunggu Verifikasi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td><span class="text-warning">Belum ada data pembayaran.</span></td>
                                        </tr>
                                    @endif
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
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" wire:click="closeProofModal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ $proofImageUrl }}" class="img-fluid rounded" alt="Bukti Pembayaran">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>