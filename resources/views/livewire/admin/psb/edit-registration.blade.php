<div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex mb-3">
        <a href="{{ route('admin.master-psb.detail-registration', ['santriId' => $santriId]) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle-fill"></i>
            Kembali ke Detail
        </a>
    </div>

    <form wire:submit.prevent="save" novalidate>
        <div class="row">
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center text-center">
                            @if($foto)
                                <img src="{{ $foto->temporaryUrl() }}" alt="Preview Foto" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @elseif($fotoSantri)
                                <img src="{{ Storage::url($fotoSantri) }}" alt="Foto Santri" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white mb-3" style="width: 150px; height: 150px;">
                                    <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            
                            <h4>{{ $editForm['nama_lengkap'] ?: 'Nama Santri' }}</h4>
                            <p class="text-secondary mb-1">NISN: {{ $editForm['nisn'] ?: '-' }}</p>
                            <p class="text-muted font-size-sm">{{ $editForm['asal_sekolah'] ?: '-' }}</p>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Ubah Foto Santri</label>
                            <input type="file" id="foto" wire:model="foto" class="form-control" accept="image/*">
                            <div wire:loading wire:target="foto" class="text-primary mt-1">Mengunggah...</div>
                            @error('foto') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <div class="form-paginate d-flex justify-content-center">
                            <button {{ $formPage == 1 ? 'disabled' : '' }} type="button" wire:click='prevForm' class="prev-form btn btn-secondary me-2">
                                <i class="bi bi-chevron-left"></i> Sebelumnya
                            </button>
                            <button {{ $formPage == 3 ? 'disabled' : '' }} type="button" wire:click='nextForm' class="next-form btn btn-info">
                                Selanjutnya <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @if ($formPage == 1)
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Halaman 1: Informasi Santri</h5></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('editForm.nama_lengkap') is-invalid @enderror" wire:model.defer="editForm.nama_lengkap">
                                    @error('editForm.nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NISN</label>
                                    <input type="text" class="form-control @error('editForm.nisn') is-invalid @enderror" wire:model.defer="editForm.nisn">
                                    @error('editForm.nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" class="form-control @error('editForm.tempat_lahir') is-invalid @enderror" wire:model.defer="editForm.tempat_lahir">
                                    @error('editForm.tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('editForm.tanggal_lahir') is-invalid @enderror" wire:model.defer="editForm.tanggal_lahir">
                                    @error('editForm.tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('editForm.jenis_kelamin') is-invalid @enderror" wire:model.defer="editForm.jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    @error('editForm.jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Agama</label>
                                    <select class="form-select @error('editForm.agama') is-invalid @enderror" wire:model.defer="editForm.agama">
                                        <option value="">Pilih Agama</option>
                                        @foreach($agamaOptions as $agama)
                                            <option value="{{ $agama }}">{{ $agama }}</option>
                                        @endforeach
                                    </select>
                                    @error('editForm.agama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control @error('editForm.email') is-invalid @enderror" wire:model.defer="editForm.email">
                                    @error('editForm.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No WhatsApp</label>
                                    <input type="text" class="form-control @error('editForm.no_whatsapp') is-invalid @enderror" wire:model.defer="editForm.no_whatsapp">
                                    @error('editForm.no_whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Asal Sekolah</label>
                                    <input type="text" class="form-control @error('editForm.asal_sekolah') is-invalid @enderror" wire:model.defer="editForm.asal_sekolah">
                                    @error('editForm.asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tahun Lulus</label>
                                    <input type="number" class="form-control @error('editForm.tahun_lulus') is-invalid @enderror" wire:model.defer="editForm.tahun_lulus" placeholder="YYYY">
                                    @error('editForm.tahun_lulus') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($formPage == 2)
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Halaman 2: Informasi Wali</h5></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Ayah</label>
                                    <input type="text" class="form-control @error('editForm.nama_ayah') is-invalid @enderror" wire:model.defer="editForm.nama_ayah">
                                    @error('editForm.nama_ayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pekerjaan Ayah</label>
                                    <input type="text" class="form-control @error('editForm.pekerjaan_ayah') is-invalid @enderror" wire:model.defer="editForm.pekerjaan_ayah">
                                    @error('editForm.pekerjaan_ayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pendidikan Ayah</label>
                                    <input type="text" class="form-control @error('editForm.pendidikan_ayah') is-invalid @enderror" wire:model.defer="editForm.pendidikan_ayah">
                                    @error('editForm.pendidikan_ayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Penghasilan Ayah</label>
                                    <input type="text" class="form-control @error('editForm.penghasilan_ayah') is-invalid @enderror" wire:model.defer="editForm.penghasilan_ayah">
                                    @error('editForm.penghasilan_ayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Ibu</label>
                                    <input type="text" class="form-control @error('editForm.nama_ibu') is-invalid @enderror" wire:model.defer="editForm.nama_ibu">
                                    @error('editForm.nama_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pekerjaan Ibu</label>
                                    <input type="text" class="form-control @error('editForm.pekerjaan_ibu') is-invalid @enderror" wire:model.defer="editForm.pekerjaan_ibu">
                                    @error('editForm.pekerjaan_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pendidikan Ibu</label>
                                    <input type="text" class="form-control @error('editForm.pendidikan_ibu') is-invalid @enderror" wire:model.defer="editForm.pendidikan_ibu">
                                    @error('editForm.pendidikan_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No Telepon Ibu</label>
                                    <input type="text" class="form-control @error('editForm.no_telp_ibu') is-invalid @enderror" wire:model.defer="editForm.no_telp_ibu">
                                    @error('editForm.no_telp_ibu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control @error('editForm.alamat') is-invalid @enderror" wire:model.defer="editForm.alamat" rows="3"></textarea>
                                    @error('editForm.alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($formPage == 3)
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Halaman 3: Informasi Dokumen</h5></div>
                        <div class="card-body">
                            <h6>Status Dokumen Saat Ini</h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Jenis Berkas</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(['Pas Foto', 'Ijazah', 'SKHUN', 'Akta Kelahiran', 'Kartu Keluarga'] as $jenisBerkas)
                                            <tr>
                                                <td>{{ $jenisBerkas }}</td>
                                                @php $file = $dokumen->where('jenis_berkas', $jenisBerkas)->first(); @endphp
                                                <td>
                                                    @if($file)
                                                        <span class="badge bg-success">Tersedia</span>
                                                    @else
                                                        <span class="badge bg-danger">Belum Diunggah</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($file)
                                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                                            <i class="bi bi-eye"></i> Lihat
                                                        </a>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" disabled>
                                                            <i class="bi bi-eye-slash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <hr>
                            <h6 class="mt-4">Unggah Dokumen Baru (Ganti yang lama)</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ijazah</label>
                                    <input type="file" class="form-control" wire:model.defer="dokumenBaru.ijazah">
                                    @error('dokumenBaru.ijazah') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">SKHUN</label>
                                    <input type="file" class="form-control" wire:model.defer="dokumenBaru.skhun">
                                    @error('dokumenBaru.skhun') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kartu Keluarga</label>
                                    <input type="file" class="form-control" wire:model.defer="dokumenBaru.kk">
                                    @error('dokumenBaru.kk') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Akta Kelahiran</label>
                                    <input type="file" class="form-control" wire:model.defer="dokumenBaru.akta">
                                    @error('dokumenBaru.akta') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="d-flex justify-content-end mt-4">
                     <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save-fill me-2"></i>
                        Simpan Semua Perubahan
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>