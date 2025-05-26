<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary">
                    <h4 class="card-title text-white">
                        Formulir Pendaftaran Santri Baru - 
                        @if ($formPage == 1)
                            Data Santri
                        @elseif ($formPage == 2)
                            Data Wali
                        @else
                            Data Alamat
                        @endif
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if ($successMessage)
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2500)" class="alert alert-success">
                                {{ $successMessage }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form class="form" wire:submit.prevent="submit">
                            @if ($formPage == 1)
                                <div class="steppers santri row">
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="foto">Foto (PNG/JPG/JPEG, Max 2MB)</label>
                                        <input type="file" class="form-control" wire:model="foto" id="foto" accept="image/png,image/jpeg,image/jpg">
                                        @error('foto') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.nama_lengkap">Nama Lengkap</label>
                                        <input type="text" class="form-control" wire:model="santriForm.nama_lengkap" id="santriForm.nama_lengkap" placeholder="Masukan Nama Lengkap">
                                        @error('santriForm.nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.nisn">NISN</label>
                                        <input type="text" class="form-control" wire:model="santriForm.nisn" id="santriForm.nisn" placeholder="Masukan NISN">
                                        @error('santriForm.nisn') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.nism">NISM</label>
                                        <input type="text" class="form-control" wire:model="santriForm.nism" id="santriForm.nism" placeholder="Masukan NISM">
                                        @error('santriForm.nism') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.npsn">NPSN</label>
                                        <input type="text" class="form-control" wire:model="santriForm.npsn" id="santriForm.npsn" placeholder="Masukan NPSN">
                                        @error('santriForm.npsn') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.kewarganegaraan">Kewarganegaraan</label>
                                        <select class="form-select" wire:model="santriForm.kewarganegaraan">
                                            <option value="">Pilih Kewarganegaraan</option>
                                            <option value="wni">WNI</option>
                                            <option value="wna">WNA</option>
                                        </select>
                                        @error('santriForm.kewarganegaraan') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.nik">NIK</label>
                                        <input type="text" class="form-control" wire:model="santriForm.nik" id="santriForm.nik" placeholder="Masukan NIK">
                                        @error('santriForm.nik') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.riwayat_penyakit">Riwayat Penyakit</label>
                                        <input type="text" class="form-control" wire:model="santriForm.riwayat_penyakit" id="santriForm.riwayat_penyakit" placeholder="Masukan Riwayat Penyakit">
                                        @error('santriForm.riwayat_penyakit') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.tempat_lahir">Tempat Lahir</label>
                                        <input type="text" class="form-control" wire:model="santriForm.tempat_lahir" id="santriForm.tempat_lahir" placeholder="Masukan Tempat Lahir">
                                        @error('santriForm.tempat_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control" wire:model="santriForm.tanggal_lahir" id="santriForm.tanggal_lahir">
                                        @error('santriForm.tanggal_lahir') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.jenis_kelamin">Jenis Kelamin</label>
                                        <select class="form-select" wire:model="santriForm.jenis_kelamin">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="putera">Laki-laki</option>
                                            <option value="puteri">Perempuan</option>
                                        </select>
                                        @error('santriForm.jenis_kelamin') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.jumlah_saudara_kandung">Jumlah Saudara Kandung</label>
                                        <input type="number" class="form-control" wire:model="santriForm.jumlah_saudara_kandung" id="santriForm.jumlah_saudara_kandung" placeholder="Masukan Jumlah">
                                        @error('santriForm.jumlah_saudara_kandung') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.anak_keberapa">Anak Keberapa</label>
                                        <input type="number" class="form-control" wire:model="santriForm.anak_keberapa" id="santriForm.anak_keberapa" placeholder="Masukan Anak Ke">
                                        @error('santriForm.anak_keberapa') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.hobi">Hobi</label>
                                        <input type="text" class="form-control" wire:model="santriForm.hobi" id="santriForm.hobi" placeholder="Masukan Hobi">
                                        @error('santriForm.hobi') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.aktivitas_pendidikan">Aktivitas Pendidikan</label>
                                        <select class="form-select" wire:model="santriForm.aktivitas_pendidikan">
                                            <option value="">Pilih Aktivitas</option>
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Nonaktif</option>
                                        </select>
                                        @error('santriForm.aktivitas_pendidikan') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.no_kip">No KIP</label>
                                        <input type="text" class="form-control" wire:model="santriForm.no_kip" id="santriForm.no_kip" placeholder="Masukan KIP">
                                        @error('santriForm.no_kip') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.no_kk">No KK</label>
                                        <input type="text" class="form-control" wire:model="santriForm.no_kk" id="santriForm.no_kk" placeholder="Masukan No KK">
                                        @error('santriForm.no_kk') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.status_santri">Status Santri</label>
                                        <select class="form-select" wire:model="santriForm.status_santri">
                                            <option value="">Pilih Status</option>
                                            <option value="reguler">Reguler</option>
                                            <option value="dhuafa">Dhuafa</option>
                                            <option value="yatim_piatu">Yatim Piatu</option>
                                        </select>
                                        @error('santriForm.status_santri') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.kelas">Kelas</label>
                                        <select class="form-select" wire:model="santriForm.kelas">
                                            <option value="">Pilih Kelas</option>
                                            <option value="SMP">SMP</option>
                                            <option value="SMA">SMA</option>
                                        </select>
                                        @error('santriForm.kelas') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.nama_kepala_keluarga">Nama Kepala Keluarga</label>
                                        <input type="text" class="form-control" wire:model="santriForm.nama_kepala_keluarga" id="santriForm.nama_kepala_keluarga" placeholder="Masukan Nama">
                                        @error('santriForm.nama_kepala_keluarga') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.no_hp_kepala_keluarga">No Telepon Kepala Keluarga</label>
                                        <input type="text" class="form-control" wire:model="santriForm.no_hp_kepala_keluarga" id="santriForm.no_hp_kepala_keluarga" placeholder="081xxxx">
                                        @error('santriForm.no_hp_kepala_keluarga') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.asal_sekolah">Asal Sekolah</label>
                                        <input type="text" class="form-control" wire:model="santriForm.asal_sekolah" id="santriForm.asal_sekolah" placeholder="Masukan Asal Sekolah">
                                        @error('santriForm.asal_sekolah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.pembiayaan">Yang Membiayai Sekolah</label>
                                        <select class="form-select" wire:model="santriForm.pembiayaan">
                                            <option value="">Pilih Pembiayaan</option>
                                            <option value="Orang Tua (Ayah/Ibu)">Orang Tua (Ayah/Ibu)</option>
                                            <option value="Beasiswa">Beasiswa</option>
                                            <option value="Wali(Kakak/Paman/Bibi)">Wali(Kakak/Paman/Bibi)</option>
                                        </select>
                                        @error('santriForm.pembiayaan') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.no_whatsapp">No WhatsApp</label>
                                        <input type="text" class="form-control" wire:model="santriForm.no_whatsapp" id="santriForm.no_whatsapp" placeholder="081xxxx">
                                        @error('santriForm.no_whatsapp') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.email">Email</label>
                                        <input type="email" class="form-control" wire:model="santriForm.email" id="santriForm.email" placeholder="Masukan Email">
                                        @error('santriForm.email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="santriForm.status_kesantrian">Status Kesantrian</label>
                                        <select class="form-select" wire:model="santriForm.status_kesantrian">
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Nonaktif</option>
                                        </select>
                                        @error('santriForm.status_kesantrian') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="ijazah">Ijazah (PDF, Max 2MB)</label>
                                        <input type="file" class="form-control" wire:model="ijazah" id="ijazah" accept="application/pdf">
                                        @error('ijazah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="kartu_keluarga">Kartu Keluarga (PDF, Max 2MB)</label>
                                        <input type="file" class="form-control" wire:model="kartu_keluarga" id="kartu_keluarga" accept="application/pdf">
                                        @error('kartu_keluarga') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="bukti_pembayaran">Bukti Pembayaran (PDF, Max 2MB)</label>
                                        <input type="file" class="form-control" wire:model="bukti_pembayaran" id="bukti_pembayaran" accept="application/pdf">
                                        @error('bukti_pembayaran') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @elseif ($formPage == 2)
                                <div class="steppers wali row">
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.nama_ayah">Nama Ayah</label>
                                        <input type="text" class="form-control" wire:model="waliForm.nama_ayah" id="waliForm.nama_ayah" placeholder="Masukan Nama Ayah">
                                        @error('waliForm.nama_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.status_ayah">Status Ayah</label>
                                        <select class="form-select" wire:model="waliForm.status_ayah">
                                            <option value="">Pilih Status</option>
                                            <option value="hidup">Hidup</option>
                                            <option value="meninggal">Meninggal</option>
                                        </select>
                                        @error('waliForm.status_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.kewarganegaraan_ayah">Kewarganegaraan Ayah</label>
                                        <select class="form-select" wire:model="waliForm.kewarganegaraan_ayah">
                                            <option value="">Pilih Kewarganegaraan</option>
                                            <option value="wni">WNI</option>
                                            <option value="wna">WNA</option>
                                        </select>
                                        @error('waliForm.kewarganegaraan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.nik_ayah">NIK Ayah</label>
                                        <input type="text" class="form-control" wire:model="waliForm.nik_ayah" id="waliForm.nik_ayah" placeholder="Masukan NIK Ayah">
                                        @error('waliForm.nik_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.tempat_lahir_ayah">Tempat Lahir Ayah</label>
                                        <input type="text" class="form-control" wire:model="waliForm.tempat_lahir_ayah" id="waliForm.tempat_lahir_ayah" placeholder="Masukan Tempat Lahir">
                                        @error('waliForm.tempat_lahir_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.tanggal_lahir_ayah">Tanggal Lahir Ayah</label>
                                        <input type="date" class="form-control" wire:model="waliForm.tanggal_lahir_ayah" id="waliForm.tanggal_lahir_ayah">
                                        @error('waliForm.tanggal_lahir_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.pendidikan_terakhir_ayah">Pendidikan Terakhir Ayah</label>
                                        <select class="form-select" wire:model="waliForm.pendidikan_terakhir_ayah">
                                            <option value="">Pilih Pendidikan</option>
                                            <option value="tidak sekolah">Tidak Sekolah</option>
                                            <option value="sd">SD</option>
                                            <option value="smp">SMP</option>
                                            <option value="sma">SMA</option>
                                            <option value="slta">SLTA</option>
                                            <option value="diploma">Diploma</option>
                                            <option value="sarjana">Sarjana</option>
                                        </select>
                                        @error('waliForm.pendidikan_terakhir_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.pekerjaan_ayah">Pekerjaan Ayah</label>
                                        <input type="text" class="form-control" wire:model="waliForm.pekerjaan_ayah" id="waliForm.pekerjaan_ayah" placeholder="Masukan Pekerjaan">
                                        @error('waliForm.pekerjaan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.penghasilan_ayah">Penghasilan Ayah</label>
                                        <input type="number" class="form-control" wire:model="waliForm.penghasilan_ayah" id="waliForm.penghasilan_ayah" placeholder="Masukan Penghasilan">
                                        @error('waliForm.penghasilan_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.no_telp_ayah">No Telepon Ayah</label>
                                        <input type="text" class="form-control" wire:model="waliForm.no_telp_ayah" id="waliForm.no_telp_ayah" placeholder="081xxxx">
                                        @error('waliForm.no_telp_ayah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.nama_ibu">Nama Ibu</label>
                                        <input type="text" class="form-control" wire:model="waliForm.nama_ibu" id="waliForm.nama_ibu" placeholder="Masukan Nama Ibu">
                                        @error('waliForm.nama_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.status_ibu">Status Ibu</label>
                                        <select class="form-select" wire:model="waliForm.status_ibu">
                                            <option value="">Pilih Status</option>
                                            <option value="hidup">Hidup</option>
                                            <option value="meninggal">Meninggal</option>
                                        </select>
                                        @error('waliForm.status_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.kewarganegaraan_ibu">Kewarganegaraan Ibu</label>
                                        <select class="form-select" wire:model="waliForm.kewarganegaraan_ibu">
                                            <option value="">Pilih Kewarganegaraan</option>
                                            <option value="wni">WNI</option>
                                            <option value="wna">WNA</option>
                                        </select>
                                        @error('waliForm.kewarganegaraan_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.nik_ibu">NIK Ibu</label>
                                        <input type="text" class="form-control" wire:model="waliForm.nik_ibu" id="waliForm.nik_ibu" placeholder="Masukan NIK Ibu">
                                        @error('waliForm.nik_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.tempat_lahir_ibu">Tempat Lahir Ibu</label>
                                        <input type="text" class="form-control" wire:model="waliForm.tempat_lahir_ibu" id="waliForm.tempat_lahir_ibu" placeholder="Masukan Tempat Lahir">
                                        @error('waliForm.tempat_lahir_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.tanggal_lahir_ibu">Tanggal Lahir Ibu</label>
                                        <input type="date" class="form-control" wire:model="waliForm.tanggal_lahir_ibu" id="waliForm.tanggal_lahir_ibu">
                                        @error('waliForm.tanggal_lahir_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.pendidikan_terakhir_ibu">Pendidikan Terakhir Ibu</label>
                                        <select class="form-select" wire:model="waliForm.pendidikan_terakhir_ibu">
                                            <option value="">Pilih Pendidikan</option>
                                            <option value="tidak sekolah">Tidak Sekolah</option>
                                            <option value="sd">SD</option>
                                            <option value="smp">SMP</option>
                                            <option value="sma">SMA</option>
                                            <option value="slta">SLTA</option>
                                            <option value="diploma">Diploma</option>
                                            <option value="sarjana">Sarjana</option>
                                        </select>
                                        @error('waliForm.pendidikan_terakhir_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.pekerjaan_ibu">Pekerjaan Ibu</label>
                                        <input type="text" class="form-control" wire:model="waliForm.pekerjaan_ibu" id="waliForm.pekerjaan_ibu" placeholder="Masukan Pekerjaan">
                                        @error('waliForm.pekerjaan_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.penghasilan_ibu">Penghasilan Ibu</label>
                                        <input type="number" class="form-control" wire:model="waliForm.penghasilan_ibu" id="waliForm.penghasilan_ibu" placeholder="Masukan Penghasilan">
                                        @error('waliForm.penghasilan_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.no_telp_ibu">No Telepon Ibu</label>
                                        <input type="text" class="form-control" wire:model="waliForm.no_telp_ibu" id="waliForm.no_telp_ibu" placeholder="081xxxx">
                                        @error('waliForm.no_telp_ibu') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="waliForm.status_orang_tua">Status Orang Tua</label>
                                        <select class="form-select" wire:model="waliForm.status_orang_tua">
                                            <option value="">Pilih Status</option>
                                            <option value="kawin">Kawin</option>
                                            <option value="cerai hidup">Cerai Hidup</option>
                                            <option value="cerai mati">Cerai Mati</option>
                                        </select>
                                        @error('waliForm.status_orang_tua') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @else
                                <div class="steppers alamat row">
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="alamatForm.status_kepemilikan_rumah">Status Kepemilikan Rumah</label>
                                        <input type="text" class="form-control" wire:model="alamatForm.status_kepemilikan_rumah" id="alamatForm.status_kepemilikan_rumah" placeholder="Masukan Status">
                                        @error('alamatForm.status_kepemilikan_rumah') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="alamatForm.provinsi">Provinsi</label>
                                        <input type="text" class="form-control" wire:model="alamatForm.provinsi" id="alamatForm.provinsi" placeholder="Masukan Provinsi">
                                        @error('alamatForm.provinsi') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="alamatForm.kabupaten">Kabupaten</label>
                                        <input type="text" class="form-control" wire:model="alamatForm.kabupaten" id="alamatForm.kabupaten" placeholder="Masukan Kabupaten">
                                        @error('alamatForm.kabupaten') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="alamatForm.kecamatan">Kecamatan</label>
                                        <input type="text" class="form-control" wire:model="alamatForm.kecamatan" id="alamatForm.kecamatan" placeholder="Masukan Kecamatan">
                                        @error('alamatForm.kecamatan') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="alamatForm.kelurahan">Kelurahan</label>
                                        <input type="text" class="form-control" wire:model="alamatForm.kelurahan" id="alamatForm.kelurahan" placeholder="Masukan Kelurahan">
                                        @error('alamatForm.kelurahan') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="alamatForm.rt">RT</label>
                                        <input type="text" class="form-control" wire:model="alamatForm.rt" id="alamatForm.rt" placeholder="Masukan RT">
                                        @error('alamatForm.rt') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="alamatForm.rw">RW</label>
                                        <input type="text" class="form-control" wire:model="alamatForm.rw" id="alamatForm.rw" placeholder="Masukan RW">
                                        @error('alamatForm.rw') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-4 mb-3">
                                        <label for="alamatForm.kode_pos">Kode Pos</label>
                                        <input type="text" class="form-control" wire:model="alamatForm.kode_pos" id="alamatForm.kode_pos" placeholder="Masukan Kode Pos">
                                        @error('alamatForm.kode_pos') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group col-lg-12 mb-3">
                                        <label for="alamatForm.alamat">Alamat Lengkap</label>
                                        <textarea class="form-control" wire:model="alamatForm.alamat" id="alamatForm.alamat" placeholder="Masukan Alamat Lengkap" rows="4"></textarea>
                                        @error('alamatForm.alamat') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <div class="form-paginate d-flex">
                                    <button type="button" class="btn btn-secondary me-1" wire:click="prevForm" {{ $formPage == 1 ? 'disabled' : '' }}>Sebelumnya</button>
                                    <button type="button" class="btn btn-primary" wire:click="nextForm" {{ $formPage == 3 ? 'disabled' : '' }}>Selanjutnya</button>
                                </div>
                                <div class="cta-buttons">
                                    <button type="button" class="btn btn-light-secondary me-1" wire:click="resetForm">Reset</button>
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                        <span wire:loading.remove>Simpan Data</span>
                                        <span wire:loading>Sedang Menyimpan...</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>