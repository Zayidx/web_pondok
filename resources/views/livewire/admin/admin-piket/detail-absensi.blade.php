<div>
    {{-- Komentar: Menggunakan CDN Bootstrap & Icons sebagai contoh. Idealnya ini ada di file layout utama Anda. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <div class="mb-3">
        <a href="{{ route('admin.piket.dashboard') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard Absensi
        </a>
    </div>
    <div class="row">
        {{-- Exam Info Card --}}
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-1">Detail Jadwal : {{ $kelas->nama }}</h5>

                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Guru</label>
                        <p>Nama Mapel</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Total Mata Pelajaran</label>
                        <p>{{ $jadwalKelasHariIni->count() }} Mata Pelajaran</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <p class="text-muted mb-0">
                            <i class="bi bi-calendar3 me-2"></i>{{ $tanggalFormatted }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Waktu</label>
                        <p> {{ $jadwalKelasHariIni->min('waktu_mulai') ? \Carbon\Carbon::parse($jadwalKelasHariIni->min('waktu_mulai'))->format('H:i') : '-' }} : {{ $jadwalKelasHariIni->max('waktu_selesai') ? \Carbon\Carbon::parse($jadwalKelasHariIni->max('waktu_selesai'))->format('H:i') : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>


        {{-- Komentar: Memeriksa apakah data kelas tersedia sebelum merender konten --}}
        @if ($kelas)

        {{-- [Main Content] Konten utama dengan gaya card yang konsisten --}}
        <div class="card rounded-4 col-md-8 mb-3">
            <div class="card-body">
                {{-- Komentar: Memeriksa apakah ada jadwal pelajaran untuk hari yang dipilih --}}
                @if ($jadwalKelasHariIni->isNotEmpty())
                {{-- [Table Section] Tabel dengan styling yang diperbaiki --}}
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="fw-semibold">No.</th>
                                <th class="fw-semibold">
                                    <i class="bi bi-book me-2"></i>Mata Pelajaran
                                </th>
                                <th class="fw-semibold">
                                    <i class="bi bi-clock me-2"></i>Waktu
                                </th>
                                <th class="fw-semibold text-center">
                                    <i class="bi bi-gear me-2"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Komentar: Melakukan loop pada data jadwal pelajaran yang ada --}}
                            @foreach ($jadwalKelasHariIni as $index => $jadwal)
                            <tr>
                                <td class="fw-medium">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">

                                        <span class="fw-medium">{{ $jadwal->mata_pelajaran }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span>
                                        {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{-- [LOGIKA DIPERTAHANKAN] Logika kondisional untuk tombol tetap sama --}}
                                    @if($isToday)
                                    <a href="{{ route('admin.piket.absensi.murid', ['jadwalId' => $jadwal->id]) }}"
                                        wire:navigate
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-qr-code-scan me-1"></i>Absensi
                                    </a>
                                    @else
                                    <a href="{{ route('admin.piket.hasil.absensi', ['jadwalId' => $jadwal->id, 'tanggal' => $tanggal]) }}"
                                        wire:navigate
                                        class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-card-checklist me-1"></i>Hasil Absensi
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                {{-- [Empty State] Pesan kosong dengan styling yang lebih menarik --}}
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                    </div>
                    <h5 class="text-muted mb-3">Tidak Ada Jadwal Pelajaran</h5>
                    <p class="text-muted mb-0">
                        Tidak ada jadwal pelajaran ditemukan untuk kelas <strong>{{ $kelas->nama }}</strong>
                        pada tanggal yang dipilih.
                    </p>
                </div>
                @endif
            </div>

        </div>
        @endif
    </div>

</div>