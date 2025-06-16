<div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex mb-2">
        <a href="{{ route('admin.master-psb.show-registrations') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left-circle-fill"></i>
            Kembali ke Daftar Pendaftaran
        </a>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <!-- Bagian Foto dan Status -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    @if($foto)
                                        <img src="{{ $foto->temporaryUrl() }}" alt="Preview Foto"
                                            class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                                    @elseif($fotoSantri)
                                        <img src="{{ Storage::url($fotoSantri) }}" alt="Foto Santri"
                                            class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                            style="width: 150px; height: 150px;">
                                            <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                                        </div>
                                    @endif
                                    <div class="mt-3">
                                        <h4>{{ $editForm['nama_lengkap'] }}</h4>
                                        <p class="text-secondary mb-1">NISN: {{ $editForm['nisn'] }}</p>
                                        <p class="text-muted font-size-sm">{{ $editForm['asal_sekolah'] }}</p>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="mb-3">
                                        <label class="form-label">Ubah Foto</label>
                                        <input type="file" wire:model="foto" class="form-control" accept="image/*">
                                        @error('foto') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-paginate d-flex justify-content-center mt-3">
                            <button {{ $formPage == 1 ? 'disabled' : '' }} type="button" wire:click='prevForm'
                                    class="prev-form btn btn-secondary me-2">Sebelumnya</button>
                            <button {{ $formPage == 3 ? 'disabled' : '' }} type="button" wire:click='nextForm'
                                    class="next-form btn btn-primary">Selanjutnya</button>
                        </div>
                    </div>

                    <!-- Bagian Detail -->
                    <div class="col-lg-8">
                        <!-- Halaman 1: Informasi Santri -->
                        @if ($formPage == 1)
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-3">Informasi Santri</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-2">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.nama_lengkap">
                                                @error('editForm.nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">NISN</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.nisn">
                                                @error('editForm.nisn') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tempat Lahir</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.tempat_lahir">
                                                @error('editForm.tempat_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Lahir</label>
                                                <input type="date" class="form-control" wire:model.defer="editForm.tanggal_lahir">
                                                @error('editForm.tanggal_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <div class="mb-3">
                                                <label class="form-label">Jenis Kelamin</label>
                                                <select class="form-select" wire:model.defer="editForm.jenis_kelamin">
                                                    <option value="">Pilih Jenis Kelamin</option>
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                                @error('editForm.jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Agama</label>
                                                <select class="form-select" wire:model.defer="editForm.agama">
                                                    <option value="">Pilih Agama</option>
                                                    @foreach($agamaOptions as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                @error('editForm.agama') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" wire:model.defer="editForm.email">
                                                @error('editForm.email') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">No WhatsApp</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.no_whatsapp">
                                                @error('editForm.no_whatsapp') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Asal Sekolah</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.asal_sekolah">
                                                @error('editForm.asal_sekolah') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tahun Lulus</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.tahun_lulus">
                                                @error('editForm.tahun_lulus') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Halaman 2: Informasi Wali -->
                        @if ($formPage == 2)
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-3">Informasi Wali</h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-2">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Ayah</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.nama_ayah">
                                                @error('editForm.nama_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Pekerjaan Ayah</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.pekerjaan_ayah">
                                                @error('editForm.pekerjaan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Pendidikan Ayah</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.pendidikan_ayah">
                                                @error('editForm.pendidikan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Penghasilan Ayah</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.penghasilan_ayah">
                                                @error('editForm.penghasilan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-2">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Ibu</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.nama_ibu">
                                                @error('editForm.nama_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Pekerjaan Ibu</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.pekerjaan_ibu">
                                                @error('editForm.pekerjaan_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Pendidikan Ibu</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.pendidikan_ibu">
                                                @error('editForm.pendidikan_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">No Telepon Ibu</label>
                                                <input type="text" class="form-control" wire:model.defer="editForm.no_telp_ibu">
                                                @error('editForm.no_telp_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">Alamat</label>
                                                <textarea class="form-control" wire:model.defer="editForm.alamat" rows="3"></textarea>
                                                @error('editForm.alamat') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Halaman 3: Informasi Dokumen -->
                        @if ($formPage == 3)
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-3">Informasi Dokumen</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Jenis Berkas</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(['Pas Foto', 'KTP', 'KK', 'Akta Kelahiran', 'Ijazah'] as $jenisBerkas)
                                                    <tr>
                                                        <td>{{ $jenisBerkas }}</td>
                                                        <td>
                                                            @if($dokumen->where('jenis_berkas', $jenisBerkas)->first())
                                                                <span class="badge bg-success">Tersedia</span>
                                                            @else
                                                                <span class="badge bg-danger">Belum Upload</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($dokumen->where('jenis_berkas', $jenisBerkas)->first())
                                                                <a href="{{ asset('storage/' . $dokumen->where('jenis_berkas', $jenisBerkas)->first()->file_path) }}" 
                                                                   target="_blank" 
                                                                   class="btn btn-sm btn-primary">
                                                                    <i class="bi bi-eye"></i> Lihat
                                                                </a>
                                                            @else
                                                                <button class="btn btn-sm btn-secondary" disabled>
                                                                    <i class="bi bi-eye-slash"></i> Tidak Tersedia
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

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
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 