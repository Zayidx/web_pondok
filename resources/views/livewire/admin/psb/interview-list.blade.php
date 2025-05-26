<div class="container mt-5">
    <!-- Pesan Sukses/Error -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="z-index: 1050;">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="z-index: 1050;">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any() && !$editInterviewModal && !$rejectModal)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Kartu Utama -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Daftar Jadwal Wawancara</h4>
        </div>
        <div class="card-body">
            <!-- Filter dan Pencarian -->
            <div class="row mb-4 g-3">
                <div class="col-md-4 col-sm-12">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" wire:model.live="search" placeholder="Cari NISN atau Nama Santri...">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-calendar"></i></span>
                        <input type="date" class="form-control" wire:model.live="tanggalWawancara" placeholder="Tanggal Wawancara">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                        <input type="time" class="form-control" wire:model.live="jamWawancara" placeholder="Jam Wawancara">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" wire:model.live="lokasiWawancara" placeholder="Lokasi/Link Wawancara">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <select class="form-select" wire:model.live="perPage">
                        <option value="5">5 per halaman</option>
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col" wire:click="sortBy('nama_lengkap')" class="cursor-pointer">
                                Nama Santri
                                @if ($sortField == 'nama_lengkap')
                                    <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }} ms-1"></i>
                                @endif
                            </th>
                            <th scope="col" wire:click="sortBy('nisn')" class="cursor-pointer">
                                NISN
                                @if ($sortField == 'nisn')
                                    <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }} ms-1"></i>
                                @endif
                            </th>
                            <th scope="col">Tanggal Wawancara</th>
                            <th scope="col">Jam Wawancara</th>
                            <th scope="col">Lokasi/Link Wawancara</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($interviews as $interview)
                            <tr>
                                <td>{{ $interview->nama_lengkap }}</td>
                                <td>{{ $interview->nisn }}</td>
                                <td>{{ $interview->jadwalWawancara->tanggal_wawancara ?? '-' }}</td>
                                <td>{{ $interview->jadwalWawancara->jam_wawancara ?? '-' }}</td>
                                <td>
                                    @if ($interview->jadwalWawancara)
                                        @if ($interview->jadwalWawancara->mode === 'online')
                                            <a href="{{ $interview->jadwalWawancara->link_online }}" target="_blank" class="text-primary">
                                                {{ Str::limit($interview->jadwalWawancara->link_online, 30) }}
                                            </a>
                                        @else
                                            {{ $interview->jadwalWawancara->lokasi_offline ?? '-' }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button wire:click="openEditInterviewModal({{ $interview->jadwalWawancara->id }})" class="btn btn-sm btn-warning me-1" title="Edit Wawancara">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button wire:click="openRejectModal({{ $interview->id }})" class="btn btn-sm btn-danger me-1" title="Tolak Santri">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        <button wire:click="cancelAcceptance({{ $interview->id }})" class="btn btn-sm btn-secondary" title="Batalkan Status">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-info-circle me-2"></i>Tidak ada data wawancara santri.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $interviews->links('vendor.livewire.bootstrap') }}
            </div>
        </div>
    </div>

    <!-- Edit Interview Modal -->
    @if ($editInterviewModal)
        <div class="modal fade show" id="editInterviewModal" tabindex="-1" role="dialog" style="display: block; z-index: 1050;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="bi bi-calendar-check me-2"></i>Edit Jadwal Wawancara</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="tanggal_wawancara" class="form-label fw-semibold">Tanggal Wawancara</label>
                            <input type="date" class="form-control" id="tanggal_wawancara" wire:model="editInterviewForm.tanggal_wawancara">
                            @error('editInterviewForm.tanggal_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="jam_wawancara" class="form-label fw-semibold">Jam Wawancara</label>
                            <input type="time" class="form-control" id="jam_wawancara" wire:model="editInterviewForm.jam_wawancara">
                            @error('editInterviewForm.jam_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="mode_wawancara" class="form-label fw-semibold">Mode Wawancara</label>
                            <select class="form-select" id="mode_wawancara" wire:model.live="editInterviewForm.mode">
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                            </select>
                            @error('editInterviewForm.mode') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @if ($editInterviewForm['mode'] === 'online')
                            <div class="mb-3">
                                <label for="link_online" class="form-label fw-semibold">Link Online</label>
                                <input type="url" class="form-control" id="link_online" wire:model="editInterviewForm.link_online" placeholder="https://zoom.us/...">
                                @error('editInterviewForm.link_online') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div class="mb-3">
                                <label for="lokasi_offline" class="form-label fw-semibold">Lokasi Offline</label>
                                <input type="text" class="form-control" id="lokasi_offline" wire:model="editInterviewForm.lokasi_offline" placeholder="Gedung Serbaguna Pesantren">
                                @error('editInterviewForm.lokasi_offline') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal"><i class="bi bi-x-circle me-2"></i>Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updateInterview"><i class="bi bi-save me-2"></i>Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
    @endif

    <!-- Reject Modal -->
    @if ($rejectModal)
        <div class="modal fade show" id="rejectModal" tabindex="-1" role="dialog" style="display: block; z-index: 1050;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Tolak Santri</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="reject_reason" class="form-label fw-semibold">Alasan Penolakan</label>
                            <textarea class="form-control" id="reject_reason" wire:model="rejectForm.reason" rows="4" placeholder="Masukkan alasan penolakan..."></textarea>
                            @error('rejectForm.reason') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal"><i class="bi bi-x-circle me-2"></i>Batal</button>
                        <button type="button" class="btn btn-danger" wire:click="reject"><i class="bi bi-check-circle me-2"></i>Tolak</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
    @endif
</div>