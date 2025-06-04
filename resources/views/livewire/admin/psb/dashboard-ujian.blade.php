    <section>
        @if (session()->has('success'))
            <div class="d-flex justify-content-end">
                <div wire:alive class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="d-flex justify-content-end">
                <div wire:alive class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">Daftar Ujian</h5>
                <div class="d-flex">
                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control me-2" placeholder="Cari ujian...">
                    <button wire:click="create" data-bs-toggle="modal" data-bs-target="#createUjianModal" class="btn btn-primary">Tambah Ujian +</button>
                </div>
            </div>

            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Ujian</th>
                            <th>Mata Pelajaran</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($this->listUjian() as $ujian)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ujian->nama_ujian }}</td>
                                <td>{{ $ujian->mata_pelajaran }}</td>
                                <td>{{ \Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d M Y') }}</td>
                                <td>{{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</td>
                                <td>{{ ucfirst($ujian->status_ujian) }}</td>
                                <td>
                                    <a href="{{ route('admin.master-ujian.detail', $ujian->id) }}" class="btn btn-info btn-sm">Soal</a>
                                    <button wire:click="edit({{ $ujian->id }})" data-bs-toggle="modal" data-bs-target="#createUjianModal" class="btn btn-warning btn-sm">Edit</button>
                                    <button wire:click="deleteUjian({{ $ujian->id }})" class="btn btn-danger btn-sm" wire:confirm="Apakah kamu ingin menghapus '{{ $ujian->nama_ujian }}'?">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada ujian!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $this->listUjian()->links() }}
                </div>
            </div>
        </div>

        <div class="modal fade" wire:ignore.self id="createUjianModal" tabindex="-1">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <form wire:submit.prevent="{{ $ujianId ? 'updateUjian' : 'createUjian' }}">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $ujianId ? 'Edit Ujian' : 'Ujian Baru' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Ujian</label>
                                <input type="text" class="form-control" wire:model.live="ujianForm.nama_ujian">
                                @error('ujianForm.nama_ujian') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <input type="text" class="form-control" wire:model.live="ujianForm.mata_pelajaran">
                                @error('ujianForm.mata_pelajaran') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Periode</label>
                                <select class="form-control" wire:model.live="ujianForm.periode_id">
                                    <option value="">Pilih Periode</option>
                                    @foreach (\App\Models\PSB\Periode::all() as $periode)
                                        <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                    @endforeach
                                </select>
                                @error('ujianForm.periode_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Ujian</label>
                                <input type="date" class="form-control" wire:model.live="ujianForm.tanggal_ujian">
                                @error('ujianForm.tanggal_ujian') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Waktu Mulai</label>
                                <input type="time" class="form-control" wire:model.live="ujianForm.waktu_mulai">
                                @error('ujianForm.waktu_mulai') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Waktu Selesai</label>
                                <input type="time" class="form-control" wire:model.live="ujianForm.waktu_selesai">
                                @error('ujianForm.waktu_selesai') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status Ujian</label>
                                <select class="form-control" wire:model.live="ujianForm.status_ujian">
                                    <option value="draft">Draft</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                                @error('ujianForm.status_ujian') <span class="text-danger">{{ $message }}</span> @endforelse
                            </div>
                        </div>
                        <div>
                        <div class="modal-footer">
                            <button type="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn {{ $ujianId ? 'btn-warning' : 'btn-primary' }}">{{ $ujianId ? 'Update' : 'Tambah' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>