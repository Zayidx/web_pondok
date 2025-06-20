<div>
    <div class="text-center mb-5">
        <h1 class="display-6 fw-bold">Absensi: {{ $jadwal->mata_pelajaran }}</h1>
        <p class="fs-5 text-muted">Kelas: {{ $jadwal->kelas->nama }} | {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    @livewire('admin.admin-piket.qr-code-generator', ['absensiId' => $absensiId], key('qr-generator-' . $absensiId))
                    
                    <hr class="my-4">
                    
                    @livewire('admin.admin-piket.live-scan-list', ['absensiId' => $absensiId], key('scan-list-' . $absensiId))
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            @livewire('admin.admin-piket.live-santri-list', ['absensiId' => $absensiId, 'kelasId' => $jadwal->kelas_id], key('santri-list-' . $absensiId))
        </div>
    </div>
</div>