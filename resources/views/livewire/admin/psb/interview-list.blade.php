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
                <div class="col-md-4">
                    <input type="text" class="form-control" wire:model.live="search" placeholder="Cari NISN atau Nama Santri...">
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
                            <th wire:click="sortBy('tanggal_wawancara')">Tanggal Wawancara @if($sortField == 'tanggal_wawancara') <i class="bi {{ $sortDirection == 'asc' ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i> @endif</th>
                            <th>Jam</th>
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
                                <td>{{ $interview->tanggal_wawancara ? $interview->tanggal_wawancara->format('d-m-Y') : '-' }}</td>
                                <td>{{ $interview->jam_wawancara ?? '-' }}</td>
                                <td>{{ ucfirst($interview->mode) ?? '-' }}</td>
                                <td>{{ $interview->mode == 'online' ? ($interview->link_online ?? '-') : ($interview->lokasi_offline ?? '-') }}</td>
                                <td>
                                    <a href="{{ route('admin.show-registration.detail', $interview->id) }}" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada jadwal wawancara.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $interviews->links() }}
        </div>
    </div>
</div>