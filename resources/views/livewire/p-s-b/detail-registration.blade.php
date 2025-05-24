<div>
    <div class="d-flex mb-2">
        <a href="{{ route('admin.show-registrations') }}" class="btn btn-primary">
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
                        @if ($santri->foto)
                            <img style="object-fit: cover"
                                 src="{{ Storage::url($santri->foto) }}"
                                 class="img-fluid w-75 rounded-2 h-75 mx-auto" alt="Foto Santri">
                        @else
                            <img src="{{ asset('dist/assets/compiled/jpg/1.jpg') }}"
                                 class="img-fluid rounded-3 w-75 mx-auto" alt="Foto Default">
                        @endif
                        <div class="mt-2">
                            <p>
                                Status Pendaftaran:
                                <span class="badge {{ in_array($santri->status_santri, ['diterima']) ? 'bg-success' : (in_array($santri->status_santri, ['ditolak']) ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($santri->status_santri) }}
                                </span>
                            </p>
                            <p>
                                Status Kesantrian:
                                <span class="badge {{ $santri->status_kesantrian == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($santri->status_kesantrian) }}
                                </span>
                            </p>
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
                            <x-more-components.form-input-basic label="NISM" name="nism"
                                value="{{ $santri->nism ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="NPSN" name="npsn"
                                value="{{ $santri->npsn ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kewarganegaraan" name="kewarganegaraan"
                                value="{{ strtoupper($santri->kewarganegaraan) ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="NIK" name="nik"
                                value="{{ $santri->nik ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Riwayat Penyakit" name="riwayat_penyakit"
                                value="{{ $santri->riwayat_penyakit ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tempat Lahir" name="tempat_lahir"
                                value="{{ $santri->tempat_lahir ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tanggal Lahir" name="tanggal_lahir"
                                value="{{ $santri->tanggal_lahir ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Jenis Kelamin" name="jenis_kelamin"
                                value="{{ $santri->jenis_kelamin == 'putera' ? 'Laki-laki' : 'Perempuan' ?? '-' }}"
                                readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Jumlah Saudara Kandung" name="jumlah_saudara_kandung"
                                value="{{ $santri->jumlah_saudara_kandung ?? '-' }} Saudara" readonly="true" />
                            <x-more-components.form-input-basic label="Anak Ke-" name="anak_ke"
                                value="{{ $santri->anak_keberapa ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Hobi" name="hobi"
                                value="{{ $santri->hobi ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Aktivitas Pendidikan" name="aktivitas_pendidikan"
                                value="{{ ucfirst($santri->aktivitas_pendidikan) ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="No KIP" name="no_kip"
                                value="{{ $santri->no_kip ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="No KK" name="no_kk"
                                value="{{ $santri->no_kk ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Status Santri" name="status_santri"
                                value="{{ ucfirst($santri->status_santri) ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kelas" name="kelas"
                                value="{{ $santri->kelas ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Nama Kepala Keluarga" name="nama_kepala_keluarga"
                                value="{{ $santri->nama_kepala_keluarga ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="No HP Kepala Keluarga" name="no_hp_kepala_keluarga"
                                value="{{ $santri->no_hp_kepala_keluarga ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Asal Sekolah" name="asal_sekolah"
                                value="{{ $santri->asal_sekolah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pembiayaan" name="pembiayaan"
                                value="{{ $santri->pembiayaan ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="No WhatsApp" name="no_whatsapp"
                                value="{{ $santri->no_whatsapp ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Email" name="email"
                                value="{{ $santri->email ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Status Kesantrian" name="status_kesantrian"
                                value="{{ ucfirst($santri->status_kesantrian) ?? '-' }}" readonly="true" />
                        </div>
                    @endif

                    <!-- Halaman 2: Informasi Wali -->
                    @if ($formPage == 2)
                        <h5 class="mb-3">Informasi Wali</h5>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Nama Ayah" name="nama_ayah"
                                value="{{ $wali->nama_ayah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Status Ayah" name="status_ayah"
                                value="{{ ucfirst($wali->status_ayah) ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kewarganegaraan Ayah" name="kewarganegaraan_ayah"
                                value="{{ strtoupper($wali->kewarganegaraan_ayah) ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="NIK Ayah" name="nik_ayah"
                                value="{{ $wali->nik_ayah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tempat Lahir Ayah" name="tempat_lahir_ayah"
                                value="{{ $wali->tempat_lahir_ayah ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Tanggal Lahir Ayah" name="tanggal_lahir_ayah"
                                value="{{ $wali->tanggal_lahir_ayah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pendidikan Terakhir Ayah" name="pendidikan_terakhir_ayah"
                                value="{{ ucfirst($wali->pendidikan_terakhir_ayah) ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pekerjaan Ayah" name="pekerjaan_ayah"
                                value="{{ $wali->pekerjaan_ayah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Penghasilan Ayah" name="penghasilan_ayah"
                                value="{{ $wali->penghasilan_ayah ? 'Rp ' . number_format($wali->penghasilan_ayah, 0, ',', '.') : '-' }}"
                                readonly="true" />
                            <x-more-components.form-input-basic label="No Telepon Ayah" name="no_telp_ayah"
                                value="{{ $wali->no_telp_ayah ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Nama Ibu" name="nama_ibu"
                                value="{{ $wali->nama_ibu ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Status Ibu" name="status_ibu"
                                value="{{ ucfirst($wali->status_ibu) ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kewarganegaraan Ibu" name="kewarganegaraan_ibu"
                                value="{{ strtoupper($wali->kewarganegaraan_ibu) ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="NIK Ibu" name="nik_ibu"
                                value="{{ $wali->nik_ibu ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tempat Lahir Ibu" name="tempat_lahir_ibu"
                                value="{{ $wali->tempat_lahir_ibu ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Tanggal Lahir Ibu" name="tanggal_lahir_ibu"
                                value="{{ $wali->tanggal_lahir_ibu ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pendidikan Terakhir Ibu" name="pendidikan_terakhir_ibu"
                                value="{{ ucfirst($wali->pendidikan_terakhir_ibu) ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pekerjaan Ibu" name="pekerjaan_ibu"
                                value="{{ $wali->pekerjaan_ibu ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Penghasilan Ibu" name="penghasilan_ibu"
                                value="{{ $wali->penghasilan_ibu ? 'Rp ' . number_format($wali->penghasilan_ibu, 0, ',', '.') : '-' }}"
                                readonly="true" />
                            <x-more-components.form-input-basic label="No Telepon Ibu" name="no_telp_ibu"
                                value="{{ $wali->no_telp_ibu ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Status Orang Tua" name="status_orang_tua"
                                value="{{ ucfirst($wali->status_orang_tua) ?? '-' }}" readonly="true" />
                        </div>
                    @endif

                    <!-- Halaman 3: Informasi Alamat dan Dokumen -->
                    @if ($formPage == 3)
                        <h5 class="mb-3">Informasi Alamat dan Dokumen</h5>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Status Kepemilikan Rumah" name="status_kepemilikan_rumah"
                                value="{{ $wali->status_kepemilikan_rumah ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Provinsi" name="provinsi"
                                value="{{ $wali->provinsi ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kabupaten" name="kabupaten"
                                value="{{ $wali->kabupaten ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kecamatan" name="kecamatan"
                                value="{{ $wali->kecamatan ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kelurahan" name="kelurahan"
                                value="{{ $wali->kelurahan ?? '-' }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="RT" name="rt"
                                value="{{ $wali->rt ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="RW" name="rw"
                                value="{{ $wali->rw ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kode Pos" name="kode_pos"
                                value="{{ $wali->kode_pos ?? '-' }}" readonly="true" />
                            <x-more-components.form-input-basic label="Alamat Lengkap" name="alamat"
                                value="{{ $wali->alamat ?? '-' }}" readonly="true" />
                        </div>
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
                                                <td>{{ ucfirst(str_replace('_', ' ', $doc->jenis_berkas)) }}</td>
                                                <td>{{ $doc->created_at ? $doc->created_at->format('d-m-Y') : '-' }}</td>
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