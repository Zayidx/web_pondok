<section>
    @if (session()->has('success'))
        <div class="d-flex justify-content-end">
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="d-flex justify-content-end">
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Informasi Ujian</h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Nama Ujian:</strong> {{ $ujian->nama_ujian }}</p>
                    <p class="mb-1"><strong>Mata Pelajaran:</strong> {{ $ujian->mata_pelajaran }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Tanggal:</strong> {{ $ujian->tanggal_ujian->format('d F Y') }}</p>
                    <p class="mb-1"><strong>Waktu:</strong> {{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Daftar Soal Ujian</h5>
            <button type="button" wire:click="create" data-bs-toggle="modal" data-bs-target="#createOrUpdateSoal"
                class="btn btn-primary">Tambah Soal +</button>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pertanyaan</th>
                        <th>Tipe Soal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->listSoal() as $soal)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ Str::limit($soal->pertanyaan, 56, '...') }}</td>
                            <td>{{ $soal->tipe_soal === 'pg' ? 'Pilihan Ganda' : 'Essay' }}</td>
                            <td>
                                <button type="button" wire:click="edit({{ $soal->id }})" data-bs-toggle="modal"
                                    data-bs-target="#createOrUpdateSoal"
                                    class="btn btn-warning btn-sm">Edit</button>
                                <button type="button" wire:click="deleteSoal({{ $soal->id }})"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah kamu ingin menghapus soal ini?')">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada soal!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="createOrUpdateSoal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit="{{$soalId ? 'updateSoal' : 'createSoal'}}">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $soalId ? 'Edit Soal' : 'Soal Baru' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tipe Soal</label>
                            <select class="form-control" wire:model.live="soalForm.tipe_soal">
                                <option value="pg">Pilihan Ganda</option>
                                <option value="essay">Essay</option>
                            </select>
                            @error('soalForm.tipe_soal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea class="form-control" required wire:model="soalForm.pertanyaan" rows="3"></textarea>
                            @error('soalForm.pertanyaan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($soalForm->tipe_soal === 'pg')
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Pilihan Jawaban</label>
                                    <button type="button" class="btn btn-sm btn-primary" wire:click="addOption">
                                        Tambah Pilihan +
                                    </button>
                                </div>
                                
                                @foreach($soalForm->opsi as $index => $option)
                                    <div class="mb-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" 
                                                wire:model="soalForm.opsi.{{$index}}.teks" 
                                                placeholder="Pilihan {{ chr(65 + $index) }}">
                                            <input type="number" class="form-control" style="max-width: 120px"
                                                wire:model="soalForm.opsi.{{$index}}.bobot" 
                                                placeholder="Bobot Nilai"
                                                min="0">
                                            @if(count($soalForm->opsi) > 2)
                                                <button type="button" class="btn btn-danger" 
                                                    wire:click="removeOption({{$index}})">
                                                    Hapus
                                                </button>
                                            @endif
                                        </div>
                                        @error("soalForm.opsi.{$index}.teks")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        @error("soalForm.opsi.{$index}.bobot")
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endforeach

                                @error('soalForm.opsi')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn {{ $soalId ? 'btn-warning' : 'btn-primary' }}">
                            {{ $soalId ? 'Update' : 'Tambah' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('close-modal', ({id}) => {
            bootstrap.Modal.getInstance(document.getElementById(id)).hide();
        });
    </script>
    @endscript
</section>