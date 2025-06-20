<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
               
                <p class="text-subtitle text-muted">Kelola pengaturan dan biaya pendaftaran ulang.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pengaturan Rekening -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Rekening</h4>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="savePengaturan">
                        <div class="mb-3">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" class="form-control" wire:model="nama_bank">
                            @error('nama_bank') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" wire:model="nomor_rekening">
                            @error('nomor_rekening') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Atas Nama</label>
                            <input type="text" class="form-control" wire:model="atas_nama">
                            @error('atas_nama') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Transfer</label>
                            <textarea class="form-control" wire:model="catatan_transfer" rows="3"></textarea>
                            @error('catatan_transfer') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Manajemen Biaya -->
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Manajemen Biaya</h4>
                </div>
                <div class="card-body">
                        <form wire:submit.prevent="saveBiaya">
                            <div class="mb-3">
                                <label class="form-label">Nama Biaya</label>
                                <input type="text" class="form-control" wire:model="nama_biaya">
                                @error('nama_biaya') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nominal</label>
                                <input type="number" class="form-control" wire:model="nominal">
                                @error('nominal') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" wire:model="keterangan" rows="2"></textarea>
                            @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" wire:model="is_active" id="is_active">
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ $is_editing ? 'Update' : 'Tambah' }} Biaya
                            </button>
                            @if($is_editing)
                                <button type="button" class="btn btn-secondary" wire:click="cancelEdit">Batal</button>
                            @endif
                        </div>
                    </form>

                    <hr class="my-4">

                    <h5>Daftar Biaya</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Biaya</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($biayas as $biaya)
                                     <tr>
        <td>{{ $biaya->nama_biaya }}</td>
        <td>Rp {{ number_format($biaya->jumlah, 0, ',', '.') }}</td>
        <td>
            <span class="badge bg-{{ $biaya->is_active ? 'success' : 'danger' }}">
                {{ $biaya->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </td>
        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-warning" wire:click="editBiaya({{ $biaya->id }})">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" wire:click="deleteBiaya({{ $biaya->id }})"
                                                        wire:confirm="Apakah Anda yakin ingin menghapus biaya ini?">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
        </tr>
@empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data biaya</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="1">Total</th>
                                    <th colspan="3">Rp {{ number_format($total_biaya, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Periode -->
    @if($periode_daftar_ulang)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Informasi Periode Daftar Ulang</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5 class="alert-heading">Batas Waktu Pendaftaran</h5>
                            <p class="mb-0">
                                Pendaftaran ulang harus diselesaikan paling lambat 
                                <strong>{{ \Carbon\Carbon::parse($periode_daftar_ulang->periode_selesai)->format('d F Y') }}</strong>.
                                Setelah batas waktu tersebut, santri yang belum melakukan pendaftaran ulang akan dianggap mengundurkan diri.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Notifikasi</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('message') }}
                </div>
            </div>
        </div>
    @endif
</div> 