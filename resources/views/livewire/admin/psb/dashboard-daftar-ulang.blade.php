<div>
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
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
                <div class="col-md-5">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari berdasarkan nama atau NISN...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filters.tipe" class="form-select">
                        @foreach($this->tipeOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filters.status" class="form-select">
                        @foreach($this->statusPaymentOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('nama_lengkap')" style="cursor: pointer;">Nama Lengkap
                                @if ($sortField === 'nama_lengkap')
                                <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('nisn')" style="cursor: pointer;">NISN
                                @if ($sortField === 'nisn')
                                <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('created_at')" style="cursor: pointer;">Tanggal Daftar
                                @if ($sortField === 'created_at')
                                <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>Status Pembayaran</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $registration)
                        <tr>
                            <td>{{ $registration->nama_lengkap }}</td>
                            <td>{{ $registration->nisn }}</td>
                            <td>{{ $registration->created_at->format('d F Y') }}</td>
                            <td>
                                @if($pembayaran = $registration->pembayaranTerbaru)
                                    @if($pembayaran->status_pembayaran === 'verified')
                                    <span class="badge bg-success">Terverifikasi</span>
                                    @elseif($pembayaran->status_pembayaran === 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @elseif($pembayaran->status_pembayaran === 'pending')
                                    <span class="badge bg-info">Menunggu Verifikasi</span>
                                    @endif
                                @else
                                <span class="badge bg-warning">Menunggu Bukti</span>
                                @endif
                            </td>
                            <td class="text-center text-nowrap">
                                @if($pembayaran = $registration->pembayaranTerbaru)
                                <div class="btn-group" role="group">
                                    <button wire:click="showDetail({{ $registration->id }})" class="btn btn-sm btn-primary" title="Detail Pendaftaran">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button wire:click="viewPaymentProof({{ $registration->id }})" class="btn btn-sm btn-info" title="Lihat Bukti Pembayaran">
                                        <i class="bi bi-file-earmark-image"></i>
                                    </button>
                                    @if(in_array($pembayaran->status_pembayaran, ['pending', 'rejected']))
                                    <button wire:click="verifyRegistration({{ $registration->id }})" wire:confirm="Anda yakin ingin MEMVERIFIKASI pendaftaran ulang santri ini?" class="btn btn-sm btn-success" title="Verifikasi Pembayaran">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button wire:click="rejectRegistration({{ $registration->id }})" wire:confirm="Anda yakin ingin MENOLAK bukti pembayaran ini? Santri harus mengupload ulang." class="btn btn-sm btn-danger" title="Tolak Pembayaran">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    @endif
                                    <button wire:click='edit("{{ $registration->id }}")' class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                                @else
                                <span class="text-muted fst-italic">Menunggu Bukti</span>
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
        </div>
        <div class="mx-3">
            {{ $registrations->links() }}
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
                                @if($pembayaran = $selectedRegistration->pembayaranTerbaru)
                                <tr>
                                    <td class="fw-bold">Bank Pengirim</td>
                                    <td>: {{ $pembayaran->bank_pengirim }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nama Pengirim</td>
                                    <td>: {{ $pembayaran->nama_pengirim }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tgl. Pembayaran</td>
                                    <td>: {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Nominal</td>
                                    <td>: Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
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
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" wire:click="closeProofModal"></button>
                    </div>
                    <div class="modal-body text-center" style="min-height: 250px; display: flex; justify-content: center; align-items: center;">
                        
                        {{-- Indikator Loading: Muncul saat ada aksi Livewire yang berjalan --}}
                        <div wire:loading>
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Memuat...</span>
                            </div>
                            <p class="mt-3">Memuat gambar...</p>
                        </div>

                        {{-- Kontainer Gambar: Dihapus dari DOM saat loading --}}
                        <div wire:loading.remove>
                            @if ($proofImageUrl)
                                <img src="{{ $proofImageUrl }}" class="img-fluid rounded" alt="Bukti Pembayaran">
                            @else
                                {{-- Pesan opsional jika gambar gagal dimuat --}}
                                <p>Gagal memuat gambar atau gambar tidak ditemukan.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {{-- Latar belakang modal --}}
        <div class="modal-backdrop fade show"></div>
    @endif

    @if($registrations->isEmpty())
    <div class="modal fade show" tabindex="-1" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informasi</h5>
                    <button type="button" class="btn-close" wire:click="$set('showEmptyModal', false)"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-inbox text-muted display-4"></i>
                    <p class="mt-3">Belum ada data pendaftaran ulang yang tersedia.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showEmptyModal', false)">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    @if($showEditModal)
    <div class="modal fade show" tabindex="-1" style="display: block;" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit.prevent="update">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data: {{ $editForm['nama_lengkap'] }}</h5>
                        <button type="button" class="btn-close" wire:click="closeEditModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" id="edit-nama_lengkap" class="form-control @error('editForm.nama_lengkap') is-invalid @enderror" wire:model.defer="editForm.nama_lengkap">
                            @error('editForm.nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit-nisn" class="form-label">NISN</label>
                            <input type="text" id="edit-nisn" class="form-control @error('editForm.nisn') is-invalid @enderror" wire:model.defer="editForm.nisn">
                            @error('editForm.nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit-asal_sekolah" class="form-label">Asal Sekolah</label>
                            <input type="text" id="edit-asal_sekolah" class="form-control @error('editForm.asal_sekolah') is-invalid @enderror" wire:model.defer="editForm.asal_sekolah">
                            @error('editForm.asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit-status_santri" class="form-label">Status Santri</label>
                            <select id="edit-status_santri" class="form-select @error('editForm.status_santri') is-invalid @enderror" wire:model.defer="editForm.status_santri">
                                <option value="menunggu">Menunggu</option>
                                <option value="wawancara">Wawancara</option>
                                <option value="sedang_ujian">Sedang Ujian</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="daftar_ulang">Daftar Ulang</option>
                            </select>
                            @error('editForm.status_santri') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeEditModal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>