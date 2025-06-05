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

    <div class="mb-3">
        <a href="{{ route('admin.master-ujian.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard Ujian
        </a>
    </div>

    <div class="row">
        {{-- Exam Info Card --}}
        <div class="col-md-4 mb-3">
    <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Ujian</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Ujian</label>
                        <p>{{ $ujian->nama_ujian }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mata Pelajaran</label>
                        <p>{{ $ujian->mata_pelajaran }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <p>{{ $ujian->tanggal_ujian->format('d F Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Waktu</label>
                        <p>{{ $ujian->waktu_mulai }} - {{ $ujian->waktu_selesai }}</p>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('admin.psb.ujian.preview', $ujian->id) }}" class="btn btn-info">
                            <i class="bi bi-eye"></i> Preview Ujian
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Questions List Card --}}
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Soal</h5>
                    <button wire:click="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOrUpdateSoal">
                        <i class="bi bi-plus-circle"></i> Tambah Soal
                    </button>
                </div>
        <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pertanyaan</th>
                                    <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->listSoal() as $soal)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                                        <td>{{ Str::limit($soal->pertanyaan, 50) }}</td>
                                        <td>
                                            <span class="badge {{ $soal->tipe_soal === 'pg' ? 'bg-info' : 'bg-warning' }}">
                                                {{ $soal->tipe_soal === 'pg' ? 'Pilihan Ganda' : 'Essay' }}
                                            </span>
                                        </td>
                            <td>
                                            <button wire:click="edit({{ $soal->id }})" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#createOrUpdateSoal">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button wire:click="deleteSoal({{ $soal->id }})" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus soal ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                                        <td colspan="4" class="text-center">Belum ada soal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create/Edit Question Modal --}}
    <div class="modal fade" id="createOrUpdateSoal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form wire:submit="{{ $soalId ? 'updateSoal' : 'createSoal' }}">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $soalId ? 'Edit Soal' : 'Tambah Soal' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tipe Soal</label>
                            <select class="form-select" wire:model.live="soalForm.tipe_soal">
                                <option value="pg">Pilihan Ganda</option>
                                <option value="essay">Essay</option>
                            </select>
                            @error('soalForm.tipe_soal')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea class="form-control" wire:model="soalForm.pertanyaan" rows="3" placeholder="Tulis pertanyaan di sini..."></textarea>
                            @error('soalForm.pertanyaan')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($soalForm->tipe_soal === 'pg')
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Pilihan Jawaban</label>
                                    <button type="button" class="btn btn-sm btn-primary" wire:click="addOption">
                                        <i class="bi bi-plus"></i> Tambah Pilihan
                                    </button>
                                </div>
                                
                                @foreach($soalForm->opsi as $index => $option)
                                    <div class="mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">{{ chr(65 + $index) }}</span>
                                            <input type="text" class="form-control" 
                                                wire:model="soalForm.opsi.{{$index}}.teks" 
                                                placeholder="Pilihan {{ chr(65 + $index) }}">
                                            <input type="number" class="form-control" style="max-width: 120px"
                                                wire:model="soalForm.opsi.{{$index}}.bobot" 
                                                placeholder="Bobot"
                                                min="0">
                                            @if(count($soalForm->opsi) > 2)
                                                <button type="button" class="btn btn-danger" 
                                                    wire:click="removeOption({{$index}})">
                                                    <i class="bi bi-trash"></i>
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
                                    <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn {{ $soalId ? 'btn-warning' : 'btn-primary' }}">
                            {{ $soalId ? 'Update' : 'Simpan' }}
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