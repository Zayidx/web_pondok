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
                <!-- Bagian Profil Santri -->
                <div class="col-lg-4 mb-4">
                    <div class="text-center">
                        @if ($fotoSantri)
                            <img style="object-fit: cover"
                                 src="{{ Storage::url($fotoSantri) }}"
                                 class="img-fluid w-75 rounded-2 h-75 mx-auto" alt="Foto Santri">
                        @else
                            <img src="{{ asset('dist/assets/compiled/jpg/1.jpg') }}"
                                 class="img-fluid rounded-3 w-75 mx-auto" alt="Foto Default">
                        @endif
                        <div class="mt-2">
                            <p>
                                Status Pendaftaran:
                                <span class="badge {{ in_array($santri->status_santri, ['diterima']) ? 'bg-success' : (in_array($santri->status_santri, ['ditolak']) ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($santri->status_santri ?? 'Menunggu') }}
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
                    <div class="form-paginate d-flex justify-content-center">
                        <button {{ $formPage == 1 ? 'disabled' : '' }} type="button" wire:click='prevForm'
                                class="prev-form btn btn-secondary me-2">Sebelumnya</button>
                        <button {{ $formPage == 3 ? 'disabled' : '' }} type="button" wire:click='nextForm'
                                class="next-form btn btn-primary">Selanjutnya</button>
                    </div>
                </div>

                <!-- Bagian Detail -->
                <div class="col-lg-8 row mb-2">
                    <!-- Halaman 1: Informasi Santri -->
                    @if ($formPage == 1)
                        <h5 class="mb-3">Informasi Santri</h5>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Nama Lengkap" name="nama_lengkap"
                                value="{{ $santri->nama_lengkap ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="NISN" name="nisn"
                                value="{{ $santri->nisn ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tempat Lahir" name="tempat_lahir"
                                value="{{ $santri->tempat_lahir ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tanggal Lahir" name="tanggal_lahir"
                                value="{{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d-m-Y') : '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Jenis Kelamin" name="jenis_kelamin"
                                value="{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Agama" name="agama"
                                value="{{ $santri->agama ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Email" name="email"
                                value="{{ $santri->email ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="No WhatsApp" name="no_whatsapp"
                                value="{{ $santri->no_whatsapp ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Asal Sekolah" name="asal_sekolah"
                                value="{{ $santri->asal_sekolah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tahun Lulus" name="tahun_lulus"
                                value="{{ $santri->tahun_lulus ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Status Santri" name="status_santri"
                                value="{{ ucfirst($santri->status_santri ?? 'Menunggu') }}" readonly="true" />
                            <x-more-components.form-input-basic label="Status Kesantrian" name="status_kesantrian"
                                value="{{ ucfirst($santri->status_kesantrian) ?? '-' }}" readonly="true" />
                        </div>
                        @if ($santri->status_santri == 'diterima')
                            <div class="col-lg-12 mb-2">
                                <h6 class="mb-2">Jadwal Wawancara</h6>
                                <div class="row">
                                    <div class="col-lg-4 mb-2">
                                        <x-more-components.form-input-basic label="Tanggal Wawancara" name="tanggal_wawancara"
                                            value="{{ $santri->tanggal_wawancara ? $santri->tanggal_wawancara->format('d-m-Y') : '-' }}" readonly="true" />
                                    </div>
                                    <div class="col-lg-4 mb-2">
                                        <x-more-components.form-input-basic label="Jam Wawancara" name="jam_wawancara"
                                            value="{{ $santri->jam_wawancara ?? '-' }}" readonly="true" />
                                    </div>
                                    <div class="col-lg-4 mb-2">
                                        <x-more-components.form-input-basic label="Mode Wawancara" name="mode"
                                            value="{{ ucfirst($santri->mode) ?? '-' }}" readonly="true" />
                                    </div>
                                    @if ($santri->mode == 'online')
                                        <div class="col-lg-4 mb-2">
                                            <x-more-components.form-input-basic label="Link Online" name="link_online"
                                                value="{{ $santri->link_online ?? '-' }}" readonly="true" />
                                        </div>
                                    @elseif ($santri->mode == 'offline')
                                        <div class="col-lg-4 mb-2">
                                            <x-more-components.form-input-basic label="Lokasi Offline" name="lokasi_offline"
                                                value="{{ $santri->lokasi_offline ?? '-' }}" readonly="true" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Halaman 2: Informasi Wali -->
                    @if ($formPage == 2)
                        <h5 class="mb-3">Informasi Wali</h5>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Nama Ayah" name="nama_ayah"
                                value="{{ $wali->nama_ayah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pekerjaan Ayah" name="pekerjaan_ayah"
                                value="{{ $wali->pekerjaan_ayah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pendidikan Ayah" name="pendidikan_ayah"
                                value="{{ $wali->pendidikan_ayah ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Penghasilan Ayah" name="penghasilan_ayah"
                                value="{{ $wali->penghasilan_ayah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Nama Ibu" name="nama_ibu"
                                value="{{ $wali->nama_ibu ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pekerjaan Ibu" name="pekerjaan_ibu"
                                value="{{ $wali->pekerjaan_ibu ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Pendidikan Ibu" name="pendidikan_ibu"
                                value="{{ $wali->pendidikan_ibu ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="No Telepon" name="no_telp_ibu"
                                value="{{ $wali->no_telp_ibu ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Alamat" name="alamat"
                                value="{{ $wali->alamat ?? '-' }}" readonly="true" />
                        </div>
                    @endif

                    <!-- Halaman 3: Informasi Dokumen -->
                    @if ($formPage == 3)
                        <h5 class="mb-3">Informasi Dokumen</h5>
                        <div class="col-lg-12 mb-2">
                            <h6 class="mb-2">Dokumen Santri</h6>
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
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>