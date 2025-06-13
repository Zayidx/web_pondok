<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Pengaturan Template Sertifikat Penerimaan</h4>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit="save">
                <div class="row">
                    <!-- Preview Section -->
                    <div class="col-md-12 mb-4">
                        <div class="border rounded p-3 bg-light">
                            <h5 class="mb-3">Preview Sertifikat</h5>
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ route('psb.surat-penerimaan.preview', 1) }}" class="rounded shadow-sm"></iframe>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="{{ route('psb.surat-penerimaan.download', 1) }}" class="btn btn-success me-2">
                                    <i class="bi bi-download"></i> Download PDF
                                </a>
                                <a href="{{ route('psb.surat-penerimaan.print', 1) }}" class="btn btn-primary" target="_blank">
                                    <i class="bi bi-printer"></i> Cetak Sertifikat
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Logo and Stamp Upload -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Logo Pesantren</label>
                            <div class="d-flex align-items-center gap-3">
                                @if($settings->logo)
                                    <img src="{{ Storage::url($settings->logo) }}" alt="Logo" class="img-thumbnail" style="height: 60px">
                                @endif
                                <input type="file" class="form-control" wire:model="logo" accept="image/*">
                            </div>
                            @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Stempel Pesantren</label>
                            <div class="d-flex align-items-center gap-3">
                                @if($settings->stempel)
                                    <img src="{{ Storage::url($settings->stempel) }}" alt="Stempel" class="img-thumbnail" style="height: 60px">
                                @endif
                                <input type="file" class="form-control" wire:model="stempel" accept="image/*">
                            </div>
                            @error('stempel') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_pesantren">Nama Pesantren</label>
                            <input type="text" class="form-control" wire:model="nama_pesantren" id="nama_pesantren">
                            @error('nama_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="nama_yayasan">Nama Yayasan</label>
                            <input type="text" class="form-control" wire:model="nama_yayasan" id="nama_yayasan">
                            @error('nama_yayasan') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="tahun_ajaran">Tahun Ajaran</label>
                            <input type="text" class="form-control" wire:model="tahun_ajaran" id="tahun_ajaran">
                            @error('tahun_ajaran') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="alamat_pesantren">Alamat Pesantren</label>
                            <input type="text" class="form-control" wire:model="alamat_pesantren" id="alamat_pesantren">
                            @error('alamat_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telepon_pesantren">Nomor Telepon</label>
                            <input type="text" class="form-control" wire:model="telepon_pesantren" id="telepon_pesantren">
                            @error('telepon_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_pesantren">Email Pesantren</label>
                            <input type="email" class="form-control" wire:model="email_pesantren" id="email_pesantren">
                            @error('email_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Tanggal Penting -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tanggal_orientasi">Tanggal Orientasi</label>
                            <input type="date" class="form-control" wire:model="tanggal_orientasi" id="tanggal_orientasi">
                            @error('tanggal_orientasi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batas_pembayaran_spp">Batas Pembayaran SPP</label>
                            <input type="date" class="form-control" wire:model="batas_pembayaran_spp" id="batas_pembayaran_spp">
                            @error('batas_pembayaran_spp') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Catatan Penting -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="catatan_penting">Catatan Penting</label>
                            <textarea class="form-control" wire:model="catatan_penting" id="catatan_penting" rows="4" placeholder="Masukkan catatan penting..."></textarea>
                            @error('catatan_penting') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Direktur dan Kepala Admin -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_direktur">Nama Direktur</label>
                            <input type="text" class="form-control" wire:model="nama_direktur" id="nama_direktur">
                            @error('nama_direktur') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nip_direktur">NIP Direktur</label>
                            <input type="text" class="form-control" wire:model="nip_direktur" id="nip_direktur">
                            @error('nip_direktur') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_kepala_admin">Nama Kepala Administrasi</label>
                            <input type="text" class="form-control" wire:model="nama_kepala_admin" id="nama_kepala_admin">
                            @error('nama_kepala_admin') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nip_kepala_admin">NIP Kepala Administrasi</label>
                            <input type="text" class="form-control" wire:model="nip_kepala_admin" id="nip_kepala_admin">
                            @error('nip_kepala_admin') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 