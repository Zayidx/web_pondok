<div>
    @if (session('success'))
        <div class="alert alert-success" style="z-index: 1050; position: relative;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" style="z-index: 1050; position: relative;">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" wire:model.live="search" placeholder="Cari NISN atau Nama Santri...">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" wire:model.live="tanggal_wawancara_filter" placeholder="Tanggal Wawancara">
                </div>
                <div class="col-md-2">
                    <input type="time" class="form-control" wire:model.live="jam_wawancara_filter" placeholder="Jam Wawancara">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model.live="lokasi_filter" placeholder="Lokasi/Link">
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
                            <th wire:click="sortBy('nama_lengkap')" class="cursor-pointer">Nama Santri @if($sortField == 'nama_lengkap') <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i> @endif</th>
                            <th wire:click="sortBy('nisn')" class="cursor-pointer">NISN @if($sortField == 'nisn') <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}</i> @endif</th>
                            <th wire:click="sortBy('tanggal_wawancara')" class="cursor-pointer">Tanggal Wawancara @if($sortField == 'tanggal_wawancara') <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}</i> @endif</th>
                            <th wire:click="sortBy('jam_wawancara')" class="cursor-pointer">Jam Wawancara @if($sortField == 'jam_wawancara') <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}</i> @endif</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($interviews as $interview)
                            <tr>
                                <td>{{ $interview->nama_lengkap }}</td>
                                <td>{{ $interview->nisn }}</td>
                                <td>{{ $interview->tanggal_wawancara ? \Carbon\Carbon::parse($interview->tanggal_wawancara)->format('d-m-Y') : '-' }}</td>
                                <td>{{ $interview->jam_wawancara ?? '-' }}</td>
                                <td>{{ $interview->mode === 'offline' ? ($interview->lokasi_offline ?? '-') : ($interview->link_online ?? '-') }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.show-registration.detail', $interview->id) }}" class="btn btn-sm btn-primary me-1">Detail</a>
                                    <button wire:click="openEditModal({{ $interview->id }})" class="btn btn-sm btn-warning me-1">Edit</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada jadwal wawancara.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $interviews->links() }}
        </div>
    </div>

    <!-- Interview Edit Modal -->
    @if($interviewModal)
        <div class="modal fade show" id="interviewModal" tabindex="-1" role="dialog" style="display: block; z-index: 1050;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit.prevent="saveInterview" wire:key="interview-modal-{{ $selectedSantriId }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Jadwal Wawancara</h5>
                            <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group mb-3">
                                <label>Tanggal Wawancara *</label>
                                <input type="date" class="form-control" wire:model="interviewForm.tanggal_wawancara">
                                @error('interviewForm.tanggal_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>Jam Wawancara *</label>
                                <input type="time" class="form-control" wire:model="interviewForm.jam_wawancara">
                                @error('interviewForm.jam_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>Mode Wawancara *</label>
                                <select class="form-select" wire:model.live="interviewForm.mode">
                                    <option value="offline">Offline</option>
                                    <option value="online">Online</option>
                                </select>
                                @error('interviewForm.mode') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            @if($interviewForm['mode'] === 'online')
                                <div class="form-group mb-3">
                                    <label>Link Online *</label>
                                    <input type="url" class="form-control" wire:model="interviewForm.link_online" placeholder="https://zoom.us/...">
                                    @error('interviewForm.link_online') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div class="form-group mb-3">
                                    <label>Lokasi Offline *</label>
                                    <input type="text" class="form-control" wire:model="interviewForm.lokasi_offline" placeholder="Gedung Serbaguna Pesantren">
                                    @error('interviewForm.lokasi_offline') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
    @endif
</div>