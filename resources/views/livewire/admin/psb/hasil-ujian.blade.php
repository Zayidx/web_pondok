<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Hasil Ujian</h3>
                <p class="text-subtitle text-muted">
                    Daftar hasil ujian santri
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            Hasil Ujian
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible show fade">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Cari nama atau NISN..." wire:model.live="search">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Santri</th>
                                <th>NISN</th>
                                <th>Mata Pelajaran</th>
                                <th>Nilai</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hasilUjians as $index => $hasil)
                                <tr>
                                    <td>{{ $hasilUjians->firstItem() + $index }}</td>
                                    <td>{{ $hasil->santri->nama_lengkap }}</td>
                                    <td>{{ $hasil->santri->nisn }}</td>
                                    <td>{{ $hasil->ujian->mata_pelajaran }}</td>
                                    <td>{{ $hasil->nilai ?? '-' }}</td>
                                    <td>
                                        @if($hasil->status === 'belum_dinilai')
                                            <span class="badge bg-warning">Belum Dinilai</span>
                                        @elseif($hasil->status === 'dinilai')
                                            <span class="badge bg-info">Sudah Dinilai</span>
                                        @elseif($hasil->status === 'dipublikasi')
                                            <span class="badge bg-success">Dipublikasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.master-ujian.periksa-ujian', ['santriId' => $hasil->santri->id, 'ujianId' => $hasil->ujian->id]) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Periksa
                                            </a>
                                            
                                            @if($hasil->status === 'dinilai')
                                                <button type="button" 
                                                        class="btn btn-sm btn-info" 
                                                        wire:click="showPublikasiConfirmation({{ $hasil->id }})">
                                                    <i class="bi bi-send"></i> Publikasi
                                                </button>
                                            @endif

                                            @if($hasil->status === 'dipublikasi' && $hasil->santri->status_santri === 'menunggu_hasil')
                                                <button type="button" 
                                                        class="btn btn-sm btn-success" 
                                                        wire:click="terimaSantri({{ $hasil->santri->id }})">
                                                    <i class="bi bi-check-circle"></i> Terima
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        wire:click="showRejectConfirmation({{ $hasil->santri->id }})">
                                                    <i class="bi bi-x-circle"></i> Tolak
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $hasilUjians->links() }}
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Publikasi -->
    <div class="modal fade" id="publikasiModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Publikasi Nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mempublikasikan nilai ujian ini?</p>
                    <p class="text-muted">Nilai akan dapat dilihat oleh santri di halaman hasil ujian mereka.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" wire:click="publikasiNilai">Publikasikan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Santri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="tolakSantri">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="alasanPenolakan">Alasan Penolakan</label>
                            <textarea id="alasanPenolakan" 
                                    class="form-control @error('alasanPenolakan') is-invalid @enderror" 
                                    wire:model="alasanPenolakan" 
                                    rows="3"
                                    required></textarea>
                            @error('alasanPenolakan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Tolak Santri</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('showPublikasiModal', () => {
                let modal = new bootstrap.Modal(document.getElementById('publikasiModal'));
                modal.show();
            });

            @this.on('hidePublikasiModal', () => {
                let modal = bootstrap.Modal.getInstance(document.getElementById('publikasiModal'));
                if (modal) {
                    modal.hide();
                }
            });

            @this.on('showRejectModal', () => {
                let modal = new bootstrap.Modal(document.getElementById('rejectModal'));
                modal.show();
            });

            @this.on('hideRejectModal', () => {
                let modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
    @endpush
</div> 
 
 
 
 
 