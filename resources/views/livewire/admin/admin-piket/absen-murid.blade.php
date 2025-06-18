<div>
    {{-- Komentar: Header halaman tetap berada di komponen induk. --}}
    <div class="text-center mb-5">
        <h1 class="display-6 fw-bold">Absensi: {{ $jadwal->mata_pelajaran }}</h1>
        <p class="fs-5 text-muted">Kelas: {{ $jadwal->kelas->nama }} | {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    {{-- Komentar: Sistem grid utama. --}}
    <div class="row g-4">
        {{-- Kolom Kiri: QR Code dan Daftar Scan Cepat --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    {{-- Komentar: Memanggil komponen anak untuk QR Code. Komponen ini tidak akan pernah di-refresh oleh polling. --}}
                    @livewire('admin.admin-piket.qr-code-generator', ['absensiId' => $absensiId], key('qr-generator-' . $absensiId))
                    
                    <hr class="my-4">
                    
                    {{-- Komentar: Memanggil komponen anak untuk daftar scan. Komponen ini memiliki polling internal sendiri. --}}
                    @livewire('admin.admin-piket.live-scan-list', ['absensiId' => $absensiId], key('scan-list-' . $absensiId))
                </div>
            </div>
        </div>

        {{-- [PERUBAHAN] Kolom Kanan: Daftar Kehadiran Lengkap --}}
        <div class="col-12 col-lg-8">
            {{-- Komentar: Memanggil komponen anak yang baru untuk menampilkan daftar kehadiran lengkap. --}}
            {{-- Komponen ini juga memiliki polling internal sendiri, terpisah dari yang lain. --}}
            @livewire('admin.admin-piket.live-santri-list', ['absensiId' => $absensiId, 'kelasId' => $jadwal->kelas_id], key('santri-list-' . $absensiId))
        </div>
    </div>
</div>
