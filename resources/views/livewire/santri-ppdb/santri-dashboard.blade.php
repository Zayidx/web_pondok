<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Selamat Datang di Dashboard Pendaftaran Santri</h4>
                    <p class="card-text">Silahkan pilih menu di sidebar untuk mengakses fitur-fitur pendaftaran santri baru.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Hasil Ujian Terakhir</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Ujian</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Tanggal Ujian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($this->listHasilUjian as $hasil)
                                <tr>
                                    <td>{{ $hasil->ujian->nama_ujian }}</td>
                                    <td>{{ $hasil->ujian->mata_pelajaran }}</td>
                                    <td>{{ $hasil->ujian->tanggal_ujian }}</td>
                                    <td>
                                        @if($hasil->status == 'lulus')
                                            <span class="badge bg-success">Lulus</span>
                                        @elseif($hasil->status == 'tidak_lulus')
                                            <span class="badge bg-danger">Tidak Lulus</span>
                                        @else
                                            <span class="badge bg-warning">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada hasil ujian</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $this->listHasilUjian->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>