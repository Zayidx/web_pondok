<div>
    <!-- Main Content -->
    <div class="card">
        <div class="card-header">
            <h4>Dashboard Pendaftaran Ulang</h4>
        </div>
        <div class="card-body">
            <!-- Search and Filter -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Cari nama atau NISN...">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <button wire:click="resetFilters" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>NISN</th>
                            <th>Tanggal Daftar</th>
                            <th>Asal Sekolah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                            <tr>
                                <td>{{ $registration->nama_lengkap }}</td>
                                <td>{{ $registration->nisn }}</td>
                                <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                                <td>{{ $registration->asal_sekolah }}</td>
                                <td>
                                    @if(!$registration->bukti_pembayaran)
                                        <span class="badge bg-warning">Menunggu Upload Bukti</span>
                                    @elseif($registration->status_santri === 'diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @else
                                        <span class="badge bg-info">Menunggu Verifikasi</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="buttons">
                                        @if($registration->bukti_pembayaran)
                                            <button wire:click="viewPaymentProof({{ $registration->id }})" class="btn btn-info btn-sm" title="Lihat Bukti Pembayaran">
                                                <i class="bi bi-file-earmark-text"></i> Lihat Bukti Pembayaran
                                            </button>
                                        @endif
                                        <button wire:click="showDetail({{ $registration->id }})" class="btn btn-primary btn-sm" title="Detail">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </button>
                                        @if($registration->bukti_pembayaran && $registration->status_santri === 'daftar_ulang')
                                            <button wire:click="verifyRegistration({{ $registration->id }})" 
                                                    wire:confirm="Apakah Anda yakin ingin menerima santri ini?"
                                                    class="btn btn-success btn-sm" title="Terima">
                                                <i class="bi bi-check-lg"></i> Diterima
                                            </button>
                                            <button wire:click="rejectRegistration({{ $registration->id }})" 
                                                    wire:confirm="Apakah Anda yakin ingin menolak bukti pembayaran ini? Santri harus mengupload ulang bukti pembayaran."
                                                    class="btn btn-danger btn-sm" title="Tolak">
                                                <i class="bi bi-x-lg"></i> Ditolak
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pendaftaran ulang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal)
        <div class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Pendaftar Ulang</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Data Santri</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td>Nama Lengkap</td>
                                        <td>: {{ $selectedRegistration->nama_lengkap }}</td>
                                    </tr>
                                    <tr>
                                        <td>NISN</td>
                                        <td>: {{ $selectedRegistration->nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jenis Kelamin</td>
                                        <td>: {{ $selectedRegistration->jenis_kelamin }}</td>
                                    </tr>
                                    <tr>
                                        <td>Asal Sekolah</td>
                                        <td>: {{ $selectedRegistration->asal_sekolah }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Data Pembayaran</h6>
                                <table class="table table-borderless">
                                    @if($selectedRegistration->bukti_pembayaran)
                                        <tr>
                                            <td>Bank Pengirim</td>
                                            <td>: {{ $selectedRegistration->bank_pengirim }}</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Pengirim</td>
                                            <td>: {{ $selectedRegistration->nama_pengirim }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Pembayaran</td>
                                            <td>: {{ optional($selectedRegistration->tanggal_pembayaran)->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>: 
                                                @if($selectedRegistration->status_santri === 'diterima')
                                                    <span class="badge bg-success">Diterima</span>
                                                @else
                                                    <span class="badge bg-info">Menunggu Verifikasi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                <span class="text-warning">Belum ada data pembayaran</span>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                        @if($selectedRegistration->bukti_pembayaran && $selectedRegistration->status_santri === 'daftar_ulang')
                            <button type="button" class="btn btn-success" wire:click="verifyRegistration({{ $selectedRegistration->id }})">
                                Terima
                            </button>
                            <button type="button" class="btn btn-danger" wire:click="rejectRegistration({{ $selectedRegistration->id }})">
                                Tolak
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Empty Proof Modal -->
    @if($showEmptyProofModal)
        <div class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informasi</h5>
                        <button type="button" class="btn-close" wire:click="closeEmptyProofModal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bukti pembayaran belum diunggah.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeEmptyProofModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @script
    <script>
        Livewire.on('openNewTab', ({ url }) => {
            window.open(url, '_blank');
        });
    </script>
    @endscript
</div> 