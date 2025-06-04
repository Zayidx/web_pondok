<section>
    @if (session()->has('success'))
        <div class="d-flex justify-content-end">
            <div wire:poll class="alert alert-success">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Hasil Ujian</h5>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Ujian</th>
                        <th>Mata Pelajaran</th>
                        <th>Tanggal</th>
                        <th>Total Skor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->listHasilUjian() as $hasil)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $hasil->ujian->nama_ujian }}</td>
                            <td>{{ $hasil->ujian->mata_pelajaran }}</td>
                            <td>{{ \Carbon\Carbon::parse($hasil->ujian->tanggal_ujian)->format('d M Y') }}</td>
                            <td>{{ $hasil->total_skor ?? 'Belum dinilai' }}</td>
                            <td>{{ ucfirst($hasil->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada hasil ujian!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>