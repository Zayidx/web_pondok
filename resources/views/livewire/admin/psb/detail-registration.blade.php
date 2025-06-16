<div>
    <div class="d-flex mb-2">
        <a href="{{ route('admin.master-psb.show-registrations') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left-circle-fill"></i>
            Kembali ke Daftar Pendaftaran
        </a>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="row">
                <!-- Bagian Foto dan Status -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                @if($fotoSantri)
                                    <img src="{{ asset('storage/' . $fotoSantri) }}" alt="Foto Santri"
                                        class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                        style="width: 150px; height: 150px;">
                                        <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                                    </div>
                                @endif
                                <div class="mt-3">
                                    <h4>{{ $santri->nama_lengkap }}</h4>
                                    <p class="text-secondary mb-1">NISN: {{ $santri->nisn }}</p>
                                    <p class="text-muted font-size-sm">{{ $santri->asal_sekolah }}</p>
                                </div>
                            </div>

                            <div class="mt-2">
                                <p>
                                    Status Pendaftaran:
                                    <span class="badge {{ in_array($santri->status_santri, ['diterima']) ? 'bg-success' : (in_array($santri->status_santri, ['ditolak']) ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($santri->status_santri ?? 'Menunggu') }}
                                    </span>
                                </p>
                                <p>
                                    Status Kesantrian:
                                    <span class="badge {{ $santri->status_kesantrian == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($santri->status_kesantrian) }}
                                    </span>
                                </p>
                                @if ($santri->reason_rejected)
                                    <p>
                                        Alasan Penolakan:
                                        <span class="text-danger">{{ $santri->reason_rejected }}</span>
                                    </p>
                                @endif
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
                                        <x-more-components.form-input-basic label="Nama Lengkap" name="nama_lengkap"
                                            value="{{ $santri->nama_lengkap ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="NISN" name="nisn"
                                            value="{{ $santri->nisn ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Tempat Lahir" name="tempat_lahir"
                                            value="{{ $santri->tempat_lahir ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Tanggal Lahir" name="tanggal_lahir"
                                            value="{{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d-m-Y') : '-' }}" readonly="true" />
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <x-more-components.form-input-basic label="Jenis Kelamin" name="jenis_kelamin"
                                            value="{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Agama" name="agama"
                                            value="{{ $santri->agama ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Email" name="email"
                                            value="{{ $santri->email ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="No WhatsApp" name="no_whatsapp"
                                            value="{{ $santri->no_whatsapp ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Asal Sekolah" name="asal_sekolah"
                                            value="{{ $santri->asal_sekolah ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Tahun Lulus" name="tahun_lulus"
                                            value="{{ $santri->tahun_lulus ?? '-' }}" readonly="true" />
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
                                        <x-more-components.form-input-basic label="Nama Ayah" name="nama_ayah"
                                            value="{{ $wali->nama_ayah ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Pekerjaan Ayah" name="pekerjaan_ayah"
                                            value="{{ $wali->pekerjaan_ayah ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Pendidikan Ayah" name="pendidikan_ayah"
                                            value="{{ $wali->pendidikan_ayah ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Penghasilan Ayah" name="penghasilan_ayah"
                                            value="{{ $wali->penghasilan_ayah ?? '-' }}" readonly="true" />
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <x-more-components.form-input-basic label="Nama Ibu" name="nama_ibu"
                                            value="{{ $wali->nama_ibu ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Pekerjaan Ibu" name="pekerjaan_ibu"
                                            value="{{ $wali->pekerjaan_ibu ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="Pendidikan Ibu" name="pendidikan_ibu"
                                            value="{{ $wali->pendidikan_ibu ?? '-' }}" readonly="true" />
                                        <x-more-components.form-input-basic label="No Telepon Ibu" name="no_telp_ibu"
                                            value="{{ $wali->no_telp_ibu ?? '-' }}" readonly="true" />
                                    </div>
                                    <div class="col-12">
                                        <x-more-components.form-input-basic label="Alamat" name="alamat"
                                            value="{{ $wali->alamat ?? '-' }}" readonly="true" />
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
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>