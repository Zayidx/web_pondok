<div>
    @if (session('success'))
        <div class="alert alert-success" style="z-index: 1050; position: relative;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" style="z-index: 1050; position: relative;">{{ session('error') }}</div>
    @endif

    @if ($errors->any() && !$interviewModal && !$rejectModal)
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
                    <input type="text" class="form-control" wire:model.live="kota" placeholder="Kota...">
                </div>
                <div class="col-md-2">
                    <select class="form-select" wire:model.live="status_santri">
                        <option value="">Semua Status</option>
                        @foreach ($statusSantriOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
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
                            <th>Kota</th>
                            <th wire:click="sortBy('status_santri')">Status Santri @if($sortField == 'status_santri') <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i> @endif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $registration)
                            <tr>
                                <td>{{ $registration->nama_lengkap }}</td>
                                <td>{{ $registration->nisn }}</td>
                                <td>{{ $registration->wali->alamat ?? '-' }}</td>
                                <td>{{ $statusSantriOptions[$registration->status_santri] ?? $registration->status_santri }}</td>
                                <td>
                                    <a href="{{ route('admin.show-registration.detail', $registration->id) }}" class="btn btn-sm btn-info">Detail</a>
                                    @if (!in_array($registration->status_santri, ['diterima', 'ditolak']))
                                        <button wire:click="openInterviewModal({{ $registration->id }})" class="btn btn-sm btn-success">Diterima</button>
                                        <button wire:click="openRejectModal({{ $registration->id }})" class="btn btn-sm btn-danger">Ditolak</button>
                                    @endif
                                    @if (in_array($registration->status_santri, ['diterima', 'ditolak']))
                                        <button wire:click="cancelStatus({{ $registration->id }})" class="btn btn-sm btn-warning">Batalkan Status</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data pendaftaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $registrations->links() }}
        </div>
    </div>

    <!-- Interview Modal -->
    @if ($interviewModal)
        <div class="modal fade show" id="interviewModal" tabindex="-1" role="dialog" style="display: block; z-index: 1040;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Jadwal Wawancara</h5>
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
                            <input type="date" class="form-control" wire:model="interviewForm.tanggal_wawancara">
                            @error('interviewForm.tanggal_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>Jam Wawancara</label>
                            <input type="time" class="form-control" wire:model="interviewForm.jam_wawancara">
                            @error('interviewForm.jam_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label>Mode Wawancara</label>
                            <select class="form-select" wire:model.live="interviewForm.mode">
                                <option value="offline">Offline</option>
                                <option value="online">Online</option>
                            </select>
                            @error('interviewForm.mode') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        @if ($interviewForm['mode'] === 'online')
                            <div class="form-group mb-3">
                                <label>Link Online</label>
                                <input type="url" class="form-control" wire:model="interviewForm.link_online" placeholder="https://zoom.us/...">
                                @error('interviewForm.link_online') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div class="form-group mb-3">
                                <label>Lokasi Offline</label>
                                <input type="text" class="form-control" wire:model="interviewForm.lokasi_offline" placeholder="Gedung Serbaguna Pesantren">
                                @error('interviewForm.lokasi_offline') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="saveInterview">Simpan</button>
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
                        <h5 class="modal-title">Tolak Pendaftaran</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
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