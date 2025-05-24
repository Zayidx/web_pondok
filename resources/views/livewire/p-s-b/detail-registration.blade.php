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
                <div class="col-lg-4 mb-4 text-center">
                    @if ($santri->foto)
                        <img style="object-fit: cover"
                            src="{{ Storage::url($santri->foto) }}"
                            class="img-fluid w-75 rounded-2 h-75 mx-auto" alt="">
                    @else
                        <img src="{{ asset('dist/assets/compiled/jpg/1.jpg') }}"
                            class="img-fluid rounded-3 w-75 mx-auto" alt="">
                    @endif

                    <div class="mt-2">
                        <p>
                            Status Pendaftaran:
                            <span class="badge {{ in_array($santri->status_santri, ['diterima']) ? 'bg-success' : (in_array($santri->status_santri, ['ditolak']) ? 'bg-danger' : 'bg-warning') }}">
                                {{ $santri->status_santri }}
                            </span>
                        </p>
                    </div>
                    <div class="form-paginate d-flex">
                        <button {{ $formPage == 1 ? 'disabled' : '' }} type="button" wire:click='prevForm'
                            class="prev-form btn">Sebelumnya</button>
                        <button {{ $formPage == 2 ? 'disabled' : '' }} type="button" wire:click='nextForm'
                            class="next-form btn text-primary">Selanjutnya</button>
                    </div>
                </div>
                <div class="col-lg-8 row mb-2">
                    @if ($formPage == 1)
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Nama Santri" name="nama_santri"
                                value="{{ $santri->nama_lengkap }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kewarganegaraan" name="kewarganegaraan"
                                value="{{ $santri->kewarganegaraan }}" readonly="true" />
                            <x-more-components.form-input-basic label="NIK" name="nik"
                                value="{{ $santri->nik }}" readonly="true" />
                            <x-more-components.form-input-basic label="Hobi" name="hobi"
                                value="{{ $santri->hobi }}" readonly="true" />
                            <x-more-components.form-input-basic label="Aktivitas Pendidikan"
                                name="aktivitas_pendidikan" value="{{ $santri->aktivitas_pendidikan }}"
                                readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="NISN" name="nisn"
                                value="{{ $santri->nisn }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tempat & Tanggal Lahir"
                                name="tempat_tanggal_lahir"
                                value="{{ $santri->tempat_lahir }}, {{ $santri->tanggal_lahir }}" readonly="true" />
                            <x-more-components.form-input-basic label="Jenis Kelamin" name="jenis_kelamin"
                                value="{{ $santri->jenis_kelamin == 'putera' ? 'Laki-laki' : 'Perempuan' }}"
                                readonly="true" />
                            <x-more-components.form-input-basic label="No KIP" name="no_kip"
                                value="{{ $santri->kip }}" readonly="true" />
                            <x-more-components.form-input-basic label="No KK" name="no_kk"
                                value="{{ $santri->no_kk }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="NISM" name="nism"
                                value="{{ $santri->nism }}" readonly="true" />
                            <x-more-components.form-input-basic label="Jumlah Saudara Kandung"
                                name="jumlah_saudara_kandung" value="{{ $santri->jumlah_saudara_kandung }} Saudara"
                                readonly="true" />
                            <x-more-components.form-input-basic label="Anak Ke-" name="anak_ke"
                                value="{{ $santri->anak_keberapa }}" readonly="true" />
                            <x-more-components.form-input-basic label="NPSN" name="npsn"
                                value="{{ $santri->npsn }}" readonly="true" />
                            <x-more-components.form-input-basic label="Riwayat Penyakit" name="riwayat_penyakit"
                                value="{{ $santri->riwayat_penyakit }}" readonly="true" />
                        </div>
                    @endif
                    @if ($formPage == 2)
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Nama Ayah" name="nama_ayah"
                                value="{{ $wali->nama_ayah }}" readonly="true" />
                            <x-more-components.form-input-basic label="NIK Ayah" name="nik_ayah"
                                value="{{ $wali->nik_ayah }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tempat Lahir Ayah" name="tempat_lahir_ayah"
                                value="{{ $wali->tempat_lahir_ayah }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tanggal Lahir Ayah" name="tanggal_lahir_ayah"
                                value="{{ $wali->tanggal_lahir_ayah }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pendidikan Ayah"
                                name="pendidikan_terakhir_ayah"
                                value="{{ ucfirst($wali->pendidikan_terakhir_ayah) }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pekerjaan Ayah" name="pekerjaan_ayah"
                                value="{{ $wali->pekerjaan_ayah }}" readonly="true" />
                            <x-more-components.form-input-basic label="No. Telepon Ayah" name="no_telp_ayah"
                                value="{{ $wali->no_telp_ayah }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Nama Ibu" name="nama_ibu"
                                value="{{ $wali->nama_ibu }}" readonly="true" />
                            <x-more-components.form-input-basic label="NIK Ibu" name="nik_ibu"
                                value="{{ $wali->nik_ibu }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tempat Lahir Ibu" name="tempat_lahir_ibu"
                                value="{{ $wali->tempat_lahir_ibu }}" readonly="true" />
                            <x-more-components.form-input-basic label="Tanggal Lahir Ibu" name="tanggal_lahir_ibu"
                                value="{{ $wali->tanggal_lahir_ibu }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pendidikan Ibu" name="pendidikan_terakhir_ibu"
                                value="{{ ucfirst($wali->pendidikan_terakhir_ibu) }}" readonly="true" />
                            <x-more-components.form-input-basic label="Pekerjaan Ibu" name="pekerjaan_ibu"
                                value="{{ $wali->pekerjaan_ibu }}" readonly="true" />
                            <x-more-components.form-input-basic label="No. Telepon Ibu" name="no_telp_ibu"
                                value="{{ $wali->no_telp_ibu }}" readonly="true" />
                        </div>
                        <div class="col-lg-4 mb-2">
                            <x-more-components.form-input-basic label="Provinsi" name="provinsi"
                                value="{{ $wali->provinsi }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kabupaten" name="kabupaten"
                                value="{{ $wali->kabupaten }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kecamatan" name="kecamatan"
                                value="{{ $wali->kecamatan }}" readonly="true" />
                            <x-more-components.form-input-basic label="Kelurahan" name="kelurahan"
                                value="{{ $wali->kelurahan }}" readonly="true" />
                            <x-more-components.form-input-basic label="RT/RW" name="rt_rw"
                                value="{{ $wali->rt }}/{{ $wali->rw }}" readonly="true" />
                            <x-more-components.form-input-basic label="Status Pernikahan" name="status_orang_tua"
                                value="{{ ucfirst($wali->status_orang_tua) }}" readonly="true" />
                        </div>
                        <div class="col-lg-12 mb-2">
                            <x-more-components.form-input-basic label="Alamat Lengkap" name="alamat"
                                value="{{ $wali->alamat }}" readonly="true" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>