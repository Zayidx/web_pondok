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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_pesantren">Nama Pesantren</label>
                            <input type="text" class="form-control" wire:model="nama_pesantren" id="nama_pesantren">
                            @error('nama_pesantren') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_yayasan">Nama Yayasan</label>
                            <input type="text" class="form-control" wire:model="nama_yayasan" id="nama_yayasan">
                            @error('nama_yayasan') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <label for="nomor_telepon">Nomor Telepon</label>
                            <input type="text" class="form-control" wire:model="nomor_telepon" id="nomor_telepon">
                            @error('nomor_telepon') <span class="text-danger">{{ $message }}</span> @enderror
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

                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="catatan_penting">Catatan Penting</label>
                            <textarea wire:model="catatan_penting" class="form-control" rows="3" placeholder="Masukkan catatan penting"></textarea>
                            @error('catatan_penting') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

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
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Template
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 