<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pengaturan Template Sertifikat Penerimaan</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="row">
                    <!-- Logo & Stempel Upload -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Logo & Stempel</h5>
                            </div>
                            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                                            <label>Logo Pesantren</label>
                                            <input type="file" class="form-control" wire:model="logo" accept="image/*">
                                            @error('logo') <span class="text-danger">{{ $message }}</span> @enderror
                                            @if($settings->logo)
                                                <div class="mt-2">
                                                    <img src="{{ Storage::url($settings->logo) }}" 
                                                         alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                                </div>
                                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                                            <label>Stempel Pesantren</label>
                                            <input type="file" class="form-control" wire:model="stempel" accept="image/*">
                                            @error('stempel') <span class="text-danger">{{ $message }}</span> @enderror
                                            @if($settings->stempel)
                                                <div class="mt-2">
                                                    <img src="{{ Storage::url($settings->stempel) }}" 
                                                         alt="Stempel" class="img-thumbnail" style="max-height: 100px;">
                                                </div>
                                            @endif
                                        </div>
                        </div>
                    </div>
                        </div>
                    </div>
                </div>

                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Pesantren</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label>Nama Pesantren</label>
                                    <input type="text" class="form-control" wire:model="nama_pesantren">
                                    @error('nama_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label>Nama Yayasan</label>
                                    <input type="text" class="form-control" wire:model="nama_yayasan">
                                    @error('nama_yayasan') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label>Alamat Pesantren</label>
                                    <textarea class="form-control" wire:model="alamat_pesantren" rows="3"></textarea>
                                    @error('alamat_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                                <div class="form-group mb-3">
                                    <label>Nomor Telepon</label>
                                    <input type="text" class="form-control" wire:model="telepon_pesantren">
                                    @error('telepon_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                                <div class="form-group mb-3">
                                    <label>Email</label>
                                    <input type="email" class="form-control" wire:model="email_pesantren">
                            @error('email_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        </div>
                    </div>
                </div>

                    <!-- Direktur Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Direktur</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label>Nama Direktur</label>
                                    <input type="text" class="form-control" wire:model="nama_direktur">
                            @error('nama_direktur') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                                <div class="form-group mb-3">
                                    <label>NIP Direktur</label>
                                    <input type="text" class="form-control" wire:model="nip_direktur">
                            @error('nip_direktur') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                        </div>
                    </div>
                </div>

                    <!-- Kepala Admin Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Informasi Kepala Admin</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label>Nama Kepala Admin</label>
                                    <input type="text" class="form-control" wire:model="nama_kepala_admin">
                            @error('nama_kepala_admin') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label>NIP Kepala Admin</label>
                                    <input type="text" class="form-control" wire:model="nip_kepala_admin">
                                    @error('nip_kepala_admin') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Penting -->
                    <div class="col-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Catatan Penting</h5>
                            </div>
                            <div class="card-body">
                        <div class="form-group">
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                              wire:model="catatan" 
                                              rows="5" 
                                              placeholder="Masukkan catatan penting untuk surat penerimaan. Gunakan koma (,) untuk memisahkan setiap catatan."></textarea>
                                    @error('catatan') 
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Contoh format: Catatan 1, Catatan 2, Catatan 3
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Pengaturan
                        </button>
                </div>
            </form>
        </div>
    </div>
</div> 