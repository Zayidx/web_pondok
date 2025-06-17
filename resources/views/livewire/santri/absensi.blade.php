<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            {{-- Komentar: Tampilkan halaman konfirmasi jika sesi valid DAN scan belum selesai. --}}
            @if ($isValidSession && !$scanCompleted)
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Konfirmasi Kehadiran</h4>
                    </div>
                    <div class="card-body text-center">
                        <p>Pastikan data di bawah ini adalah benar milik Anda sebelum melanjutkan.</p>
                        
                        {{-- Komentar: Tampilkan foto santri jika ada, jika tidak, tampilkan inisial. --}}
                        @if ($santri->foto)
                            <img src="{{ asset('storage/' . $santri->foto) }}" class="rounded-circle mb-3 object-cover" width="100" height="100" alt="Foto Santri">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center mx-auto mb-3" style="width: 100px; height: 100px;">
                                <span style="font-size: 2.5rem;">{{ strtoupper(substr($santri->nama, 0, 1)) }}</span>
                            </div>
                        @endif

                        <h5 class="card-title">{{ $santri->nama }}</h5>
                        <ul class="list-group list-group-flush text-left">
                            <li class="list-group-item"><strong>NISN:</strong> {{ $santri->nisn ?? '-' }}</li>
                            {{-- Komentar: Memuat relasi kelas untuk menampilkan nama kelas --}}
                            <li class="list-group-item"><strong>Kelas:</strong> {{ $santri->kelas->nama ?? 'Tidak ada data' }}</li>
                        </ul>

                        <div class="mt-4">
                            {{-- Komentar: Tombol Konfirmasi --}}
                            <button wire:click="confirmScan" wire:loading.attr="disabled" class="btn btn-success btn-lg">
                                <span wire:loading.remove>✅ Ya, Konfirmasi Kehadiran Saya</span>
                                <span wire:loading>Memproses...</span>
                            </button>

                            {{-- Komentar: Tombol Batal --}}
                            <a href="{{ route('santri.scanner') }}" wire:navigate class="btn btn-danger btn-lg">Batal</a>
                        </div>
                    </div>
                </div>

            {{-- Komentar: Tampilkan halaman hasil setelah konfirmasi atau jika sesi tidak valid. --}}
            @else
                <div class="card shadow-sm text-center">
                    <div class="card-body p-5">
                        @if ($status === 'success')
                            <div class="text-success mb-3" style="font-size: 4rem;">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <h2>Berhasil!</h2>
                            <p class="lead">{{ $message }}</p>
                        @else
                            <div class="text-danger mb-3" style="font-size: 4rem;">
                                <i class="bi bi-x-circle-fill"></i>
                            </div>
                            <h2>Gagal!</h2>
                            <p class="lead">{{ $message }}</p>
                        @endif
                        <a href="{{ route('santri.dashboard') }}" wire:navigate class="btn btn-primary mt-3">Kembali ke Dashboard</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
