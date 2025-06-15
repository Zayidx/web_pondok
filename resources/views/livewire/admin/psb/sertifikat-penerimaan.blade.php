<div>
    <div class="page-title">
        <p class="text-subtitle text-muted">Atur informasi, logo, dan stempel yang akan tampil pada sertifikat kelulusan.</p>
    </div>

    <div class="card">
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form wire:submit.prevent="save">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                    <button wire:click.prevent="$set('activeTab', 'umum')" class="nav-link {{ $activeTab == 'umum' ? 'active' : '' }}" id="umum-tab" data-bs-toggle="tab" data-bs-target="#umum" type="button" role="tab" aria-controls="umum" aria-selected="true">
                            <i class="bi bi-info-circle-fill me-2"></i>Informasi Umum
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button wire:click.prevent="$set('activeTab', 'pejabat')" class="nav-link {{ $activeTab == 'pejabat' ? 'active' : '' }}" id="pejabat-tab" data-bs-toggle="tab" data-bs-target="#pejabat" type="button" role="tab" aria-controls="pejabat" aria-selected="false">
                            <i class="bi bi-person-badge-fill me-2"></i>Pejabat
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button wire:click.prevent="$set('activeTab', 'catatan')" class="nav-link {{ $activeTab == 'catatan' ? 'active' : '' }}" id="catatan-tab" data-bs-toggle="tab" data-bs-target="#catatan" type="button" role="tab" aria-controls="catatan" aria-selected="false">
                            <i class="bi bi-pencil-fill me-2"></i>Catatan Tambahan
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade {{ $activeTab == 'umum' ? 'show active' : '' }}" id="umum" role="tabpanel" aria-labelledby="umum-tab">
                        <div class="row pt-4">
                            <div class="col-lg-7">
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Pesantren</label>
                                    <input type="text" class="form-control" wire:model="nama_pesantren" placeholder="Contoh: Pondok Pesantren Modern">
                                    @error('nama_pesantren') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Yayasan</label>
                                    <input type="text" class="form-control" wire:model="nama_yayasan" placeholder="Contoh: Yayasan Pendidikan Islam">
                                    @error('nama_yayasan') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">Alamat Pesantren</label>
                                    <textarea class="form-control" wire:model="alamat_pesantren" rows="3" placeholder="Jl. Pendidikan No. 123, Kota..."></textarea>
                                    @error('alamat_pesantren') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Nomor Telepon</label>
                                            <input type="text" class="form-control" wire:model="telepon_pesantren" placeholder="0812-3456-7890">
                                            @error('telepon_pesantren') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" wire:model="email_pesantren" placeholder="info@pesantren.com">
                                            @error('email_pesantren') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group mb-4">
                                    <label class="form-label mb-2">Logo Pesantren</label>
                                    <div class="d-flex align-items-start gap-3">
                                        @if ($logo)
                                            <img src="{{ $logo->temporaryUrl() }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain;">
                                        @elseif($settings->logo)
                                            <img src="{{ Storage::url($settings->logo) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain;">
                                        @endif
                                        <div class="w-100">
                                            <input type="file" class="form-control" wire:model="logo" accept="image/png, image/jpeg">
                                            <div wire:loading wire:target="logo" class="text-muted small mt-1">Mengunggah...</div>
                                            @error('logo') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade {{ $activeTab == 'pejabat' ? 'show active' : '' }}" id="pejabat" role="tabpanel" aria-labelledby="pejabat-tab">
                        <div class="row pt-4">
                            <div class="col-md-6">
                                <h5 class="mb-3">Informasi Direktur</h5>
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Direktur</label>
                                    <input type="text" class="form-control" wire:model="nama_direktur" placeholder="Nama lengkap direktur">
                                    @error('nama_direktur') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">NIP Direktur</label>
                                    <input type="text" class="form-control" wire:model="nip_direktur" placeholder="NIP/NIK Direktur">
                                    @error('nip_direktur') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label mb-2">TTD Direktur</label>
                                    <div class="d-flex align-items-start gap-3">
                                        @if ($ttd_direktur)
                                            <img src="{{ $ttd_direktur->temporaryUrl() }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain;">
                                        @elseif($settings->ttd_direktur)
                                            <img src="{{ Storage::url($settings->ttd_direktur) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain;">
                                        @endif
                                        <div class="w-100">
                                            <input type="file" class="form-control" wire:model="ttd_direktur" accept="image/png, image/jpeg">
                                            <div wire:loading wire:target="ttd_direktur" class="text-muted small mt-1">Mengunggah...</div>
                                            @error('ttd_direktur') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-3">Informasi Kepala Admin</h5>
                                <div class="form-group mb-3">
                                    <label class="form-label">Nama Kepala Admin</label>
                                    <input type="text" class="form-control" wire:model="nama_kepala_admin" placeholder="Nama lengkap kepala administrasi">
                                    @error('nama_kepala_admin') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="form-label">NIP Kepala Admin</label>
                                    <input type="text" class="form-control" wire:model="nip_kepala_admin" placeholder="NIP/NIK Kepala Admin">
                                    @error('nip_kepala_admin') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label mb-2">TTD Administrasi</label>
                                    <div class="d-flex align-items-start gap-3">
                                        @if ($ttd_admin)
                                            <img src="{{ $ttd_admin->temporaryUrl() }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain;">
                                        @elseif($settings->ttd_admin)
                                            <img src="{{ Storage::url($settings->ttd_admin) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain;">
                                        @endif
                                        <div class="w-100">
                                            <input type="file" class="form-control" wire:model="ttd_admin" accept="image/png, image/jpeg">
                                            <div wire:loading wire:target="ttd_admin" class="text-muted small mt-1">Mengunggah...</div>
                                            @error('ttd_admin') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade {{ $activeTab == 'catatan' ? 'show active' : '' }}" id="catatan" role="tabpanel" aria-labelledby="catatan-tab">
                         <div class="pt-4">
                            <div class="form-group">
                                <label class="form-label">Catatan Penting</label>
                                <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                          wire:model="catatan" 
                                          rows="5" 
                                          placeholder="Masukkan catatan penting untuk surat penerimaan."></textarea>
                                @error('catatan') 
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Setiap baris akan dianggap sebagai satu poin catatan pada sertifikat.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white text-end mt-4 px-0">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save"><i class="bi bi-save me-2"></i>Simpan Pengaturan</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>