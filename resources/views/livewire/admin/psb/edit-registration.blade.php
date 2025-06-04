<div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <!-- Left Column - Photo -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Foto</h5>
                        <div class="text-center mb-3">
                            @if ($foto)
                                <img src="{{ $foto->temporaryUrl() }}" class="img-fluid w-75 rounded-2 h-75 mx-auto" alt="Preview Foto">
                            @elseif ($fotoSantri)
                                <img src="{{ Storage::url($fotoSantri) }}" class="img-fluid w-75 rounded-2 h-75 mx-auto" alt="Foto Santri">
                            @else
                                <img src="{{ asset('dist/assets/compiled/jpg/1.jpg') }}" class="img-fluid rounded-3 w-75 mx-auto" alt="Foto Default">
                            @endif
                            <div class="mt-2">
                                <input type="file" wire:model="foto" class="form-control" accept="image/*">
                                @error('foto') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-paginate d-flex justify-content-center mt-3">
                            <button {{ $formPage == 1 ? 'disabled' : '' }} type="button" wire:click='prevForm'
                                    class="prev-form btn btn-secondary me-2">Sebelumnya</button>
                            <button {{ $formPage == 3 ? 'disabled' : '' }} type="button" wire:click='nextForm'
                                    class="next-form btn btn-primary">Selanjutnya</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form wire:submit.prevent="save">
                            @if ($formPage == 1)
                                <h5 class="mb-3">Informasi Santri</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.nama_lengkap">
                                            @error('editForm.nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">NISN *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.nisn">
                                            @error('editForm.nisn') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tempat Lahir *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.tempat_lahir">
                                            @error('editForm.tempat_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Lahir *</label>
                                            <input type="date" class="form-control" wire:model.defer="editForm.tanggal_lahir">
                                            @error('editForm.tanggal_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Tipe Pendaftaran *</label>
                                            <select class="form-select" wire:model.defer="editForm.tipe_pendaftaran">
                                                <option value="">Pilih Tipe Pendaftaran</option>
                                                @foreach($tipeOptions as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('editForm.tipe_pendaftaran') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jenis Kelamin *</label>
                                            <select class="form-select" wire:model.defer="editForm.jenis_kelamin">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                            @error('editForm.jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Agama *</label>
                                            <select class="form-select" wire:model.defer="editForm.agama">
                                                <option value="">Pilih Agama</option>
                                                @foreach($agamaOptions as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('editForm.agama') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email *</label>
                                            <input type="email" class="form-control" wire:model.defer="editForm.email">
                                            @error('editForm.email') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No WhatsApp *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.no_whatsapp">
                                            @error('editForm.no_whatsapp') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Asal Sekolah *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.asal_sekolah">
                                            @error('editForm.asal_sekolah') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tahun Lulus *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.tahun_lulus">
                                            @error('editForm.tahun_lulus') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">NIK *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.nik">
                                            @error('editForm.nik') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No. KK *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.no_kk">
                                            @error('editForm.no_kk') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status Santri *</label>
                                            <select class="form-select" wire:model.defer="editForm.status_santri">
                                                <option value="">Pilih Status</option>
                                                @foreach($statusOptions as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('editForm.status_santri') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($formPage == 2)
                                <h5 class="mb-3">Informasi Wali</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Ayah *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.nama_ayah">
                                            @error('editForm.nama_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Pekerjaan Ayah *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.pekerjaan_ayah">
                                            @error('editForm.pekerjaan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Pendidikan Ayah *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.pendidikan_ayah">
                                            @error('editForm.pendidikan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Penghasilan Ayah *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.penghasilan_ayah">
                                            @error('editForm.penghasilan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Ibu *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.nama_ibu">
                                            @error('editForm.nama_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Pekerjaan Ibu *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.pekerjaan_ibu">
                                            @error('editForm.pekerjaan_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Pendidikan Ibu *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.pendidikan_ibu">
                                            @error('editForm.pendidikan_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No Telepon Ibu *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.no_telp_ibu">
                                            @error('editForm.no_telp_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat *</label>
                                            <textarea class="form-control" wire:model.defer="editForm.alamat" rows="3"></textarea>
                                            @error('editForm.alamat') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Desa/Kelurahan *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.desa">
                                            @error('editForm.desa') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kecamatan *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.kecamatan">
                                            @error('editForm.kecamatan') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kabupaten/Kota *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.kabupaten">
                                            @error('editForm.kabupaten') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Provinsi *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.provinsi">
                                            @error('editForm.provinsi') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Pos *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.kode_pos">
                                            @error('editForm.kode_pos') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">No HP *</label>
                                            <input type="text" class="form-control" wire:model.defer="editForm.no_hp">
                                            @error('editForm.no_hp') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($formPage == 3)
                                <h5 class="mb-3">Informasi Dokumen</h5>
                                <div class="row">
                                    <div class="col-lg-12 mb-2">
                                        <h6 class="mb-2">Dokumen yang Ada</h6>
                                        @if ($dokumen->isEmpty())
                                            <p class="text-muted">Belum ada dokumen yang diunggah untuk santri ini.</p>
                                        @else
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Jenis Dokumen</th>
                                                        <th>Tanggal Unggah</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dokumen as $doc)
                                                        <tr>
                                                            <td>{{ ucfirst($doc->jenis_berkas) }}</td>
                                                            <td>{{ $doc->tanggal ? $doc->tanggal->format('d-m-Y') : '-' }}</td>
                                                            <td>
                                                                <a href="{{ Storage::url($doc->file_path) }}"
                                                                   target="_blank"
                                                                   class="btn btn-sm btn-primary">
                                                                    Lihat
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif

                                        <h6 class="mt-4 mb-2">Unggah Dokumen Baru</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Kartu Keluarga</label>
                                            <input type="file" class="form-control" wire:model.defer="dokumenBaru.kk">
                                            @error('dokumenBaru.kk') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Akta Kelahiran</label>
                                            <input type="file" class="form-control" wire:model.defer="dokumenBaru.akta">
                                            @error('dokumenBaru.akta') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ijazah</label>
                                            <input type="file" class="form-control" wire:model.defer="dokumenBaru.ijazah">
                                            @error('dokumenBaru.ijazah') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-12">
                                    <a href="{{ route('admin.master-psb.show-registrations') }}" class="btn btn-secondary me-2">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 