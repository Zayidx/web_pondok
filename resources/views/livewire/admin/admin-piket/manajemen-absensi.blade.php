<div>
    <div class="page-heading">
        <h3>Manajemen Sesi Absensi</h3>
        <p>Hari: {{ $hariIni }}</p>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Jadwal Pelajaran Hari Ini</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwalPelajaran as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->kategoriPelajaran->nama_pelajaran }}</td>
                                    <td>{{ $jadwal->kelas->nama_kelas }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</td>
                                    <td>
                                       <a href="{{ route('superadmin.piket.mulai.sesi', $jadwal->id) }}" class="btn btn-primary btn-sm" wire:navigate>Mulai Sesi & Tampilkan QR</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada jadwal pelajaran untuk hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>