<div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>Daftar Wawancara Santri</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Cari nama atau NISN...">
                </div>
                <div class="col-md-3">
                    <select wire:model="perPage" class="form-select">
                        <option value="10">10 per halaman</option>
                        <option value="25">25 per halaman</option>
                        <option value="50">50 per halaman</option>
                        <option value="100">100 per halaman</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('nama_lengkap')" style="cursor: pointer;">
                                Nama Lengkap
                                @if ($sortField === 'nama_lengkap')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>NISN</th>
                            <th wire:click="sortBy('tanggal_wawancara')" style="cursor: pointer;">
                                Jadwal
                                @if ($sortField === 'tanggal_wawancara')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>Mode</th>
                            <th>Lokasi/Link</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($interviews as $interview)
                            <tr>
                                <td>{{ $interview->nama_lengkap }}</td>
                                <td>{{ $interview->nisn }}</td>
                                <td>{{ \Carbon\Carbon::parse($interview->tanggal_wawancara)->format('d F Y H:i') }}</td>
                                <td>{{ ucfirst($interview->mode) }}</td>
                                <td>
                                    @if($interview->mode === 'online')
                                        <a href="{{ $interview->link_online }}" target="_blank">{{ $interview->link_online }}</a>
                                    @else
                                        {{ $interview->lokasi_offline }}
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <button wire:click="openEditModal({{ $interview->id }})" 
                                            class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil"></i> Edit Jadwal
                                    </button>
                                    <button wire:click="acceptSantri({{ $interview->id }})" 
                                            class="btn btn-sm btn-success me-1"
                                            onclick="return confirm('Apakah Anda yakin ingin menerima santri ini?')">
                                        <i class="bi bi-check-circle"></i> Terima
                                    </button>
                                    <button wire:click="rejectSantri({{ $interview->id }})" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Apakah Anda yakin ingin menolak santri ini?')">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
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

            <div class="mt-4">
                {{ $interviews->links() }}
            </div>
        </div>
    </div>

    <!-- Edit Interview Modal -->
    @if($showEditModal)
        <div class="modal fade show" id="editInterviewModal" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Jadwal Wawancara</h5>
                        <button type="button" class="btn-close" wire:click="closeEditModal"></button>
                    </div>
                    <form wire:submit.prevent="saveInterview">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Wawancara</label>
                                <input type="date" class="form-control" wire:model="interviewForm.tanggal_wawancara" min="{{ now()->format('Y-m-d') }}">
                                @error('interviewForm.tanggal_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Waktu Wawancara</label>
                                <input type="time" class="form-control" wire:model="interviewForm.jam_wawancara">
                                @error('interviewForm.jam_wawancara') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mode Wawancara</label>
                                <select class="form-select" wire:model="interviewForm.mode">
                                    <option value="offline">Offline</option>
                                    <option value="online">Online</option>
                                </select>
                                @error('interviewForm.mode') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            @if($interviewForm['mode'] === 'online')
                                <div class="mb-3">
                                    <label class="form-label">Link Meeting</label>
                                    <input type="url" class="form-control" wire:model="interviewForm.link_online" placeholder="https://meet.google.com/...">
                                    @error('interviewForm.link_online') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div class="mb-3">
                                    <label class="form-label">Lokasi Wawancara</label>
                                    <input type="text" class="form-control" wire:model="interviewForm.lokasi_offline" placeholder="Ruang Meeting Lt. 2">
                                    @error('interviewForm.lokasi_offline') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeEditModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>