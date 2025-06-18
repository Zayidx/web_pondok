<div>
    <div class="page-heading">
        <h3>Absensi: {{ $jadwal->kategoriPelajaran->nama_pelajaran }}</h3>
        <p>Kelas: {{ $jadwal->kelas->nama_kelas }} | Hari: {{ $jadwal->hari }} | Jam: {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</p>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Scan QR Code Ini</h4>
                    </div>
                    <div class="card-body text-center" wire:poll.55s="generateQrToken">
                        @if ($qrToken)
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate($qrToken) !!}
                        @endif
                        <p class="mt-2">QR Code akan diperbarui secara otomatis.</p>
                        <div wire:loading>
                            <p>Membuat QR Code baru...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Santri Hadir ({{ $this->kehadiranHariIni->count() }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Santri</th>
                                        <th>Waktu Hadir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($this->kehadiranHariIni as $index => $kehadiran)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $kehadiran->santri->nama_lengkap }}</td>
                                            <td>{{ $kehadiran->waktu_hadir->format('H:i:s') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Belum ada santri yang melakukan absensi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>