<div>
    @if (session('success'))
        <div class="alert alert-success" style="z-index: 1050; position: relative;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" style="z-index: 1050; position: relative;">{{ session('error') }}</div>
    @endif

    @if ($errors->any() && !$editInterviewModal && !$rejectModal)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" wire:model.live="search" placeholder="Cari NISN atau Nama Santri...">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" wire:model.live="tanggalWawancara" placeholder="Tanggal Wawancara">
                </div>
                <div class="col-md-2">
                    <input type="time" class="form-control" wire:model.live="jamWawancara" placeholder="Jam Wawancara">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model.live="lokasiWawancara" placeholder="Lokasi/Link Wawancara">
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('nama_lengkap')">Nama Santri @if($sortField == 'nama_lengkap') <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i> @endif</th>
                            <th wire:click="sortBy('nisn')">NISN @if($sortField == 'nisn') <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i> @endif</th>
                            <th>Tanggal Wawancara</th>
                            <th>Jam Wawancara</th>
                            <th>Lokasi/Link Wawancara</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($interviews as $interview)
                            <tr>
                                <td>{{ $interview->nama_lengkap }}</td>
                                <td>{{ $interview->nisn }}</td>
                                <td>{{ $interview->jadwalWawancara->tanggal_wawancara ?? '-' }}</td>
                                <td>{{ $interview->jadwalWawancara->jam_wawancara ?? '-' }}</td>
                                <td>{{ $interview->jadwalWawancara->lokasi_offline ?? $interview->jadwalWawancara->link_online ?? '-' }}</td>
                                <td>
                                    <button wire:click="openEditInterviewModal({{ $interview->jadwalWawancara->id }})" class="btn btn-sm btn-warning">Edit Wawancara</button>
                                    <button wire:click="openRejectModal({{ $interview->id }})" class="btn btn-sm btn-danger">Tolak</button>
                                    <button wire:click="cancelAcceptance({{ $interview->id }})" class="btn btn-sm btn-secondary">Batalkan Status</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data wawancara santri.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $interviews->links() }}
        </div>
    </div>

    <!-- Edit Interview Modal -->
    @if ($editInterviewModal)
        <div class="modal fade show" id="editInterviewModal" tabindex="-1" role="dialog" style="display: block; z-index: 1040;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Jadwal Wawancara</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group mb-3">
                            <label>Tanggal Wawancara</label>
                            <input type="date" class="form-control" wire:model="editInterviewForm.tanggal_wawancara">
                            @error('editInterviewForm.tanggal_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>Jam Wawancara</label>
                            <input type="time" class="form-control" wire:model="editInterviewForm.jam_wawancara">
                            @error('editInterviewForm.jam_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>Mode Wawancara</label>
                            <select class="form-select" wire:model.live="editInterviewForm.mode">
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                            </select>
                            @error('editInterviewForm.mode') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @if ($editInterviewForm['mode'] === 'online')
                            <div class="form-group mb-3">
                                <label>Link Online</label>
                                <input type="url" class="form-control" wire:model="editInterviewForm.link_online" placeholder="https://zoom.us/...">
                                @error('editInterviewForm.link_online') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div class="form-group mb-3">
                                <label>Lokasi Offline</label>
                                <input type="text" class="form-control" wire:model="editInterviewForm.lokasi_offline" placeholder="Gedung Serbaguna Pesantren">
                                @error('editInterviewForm.lokasi_offline') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="updateInterview">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" style="z-index: 1030;"></div>
    @endif

    <!-- Reject Modal -->
    @if ($rejectModal)
        <div class="modal fade show" id="rejectModal" tabindex="-1" role="dialog" style="display: block; z-index: 1040;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Santri</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group mb-3">
                            <label>Alasan Penolakan</label>
                            <textarea class="form-control" wire:model="rejectForm.reason" rows="4" placeholder="Masukkan alasan penolakan..."></textarea>
                            @error('rejectForm.reason') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-danger" wire:click="reject">Tolak</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" style="z-index: 1030;"></div>
    @endif
</div>