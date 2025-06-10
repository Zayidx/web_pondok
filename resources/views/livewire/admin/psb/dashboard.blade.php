<div>
    

    <div>
        <section>
            <div class="row">
                <div class="col-lg-3">
                    <x-card.card-basic title="Total Pendaftar" value="{{ $totalPendaftar }}" subValue="Santri"
                        iconClass="bi bi-people-fill" textColor="purple" />
                </div>

                <div class="col-lg-3">
                    <x-card.card-basic title="Periode Aktif" value="{{ $periode ? $periode->nama_periode : 'Tidak ada' }}" subValue=""
                        iconClass="bi bi-calendar-check" textColor="purple" />
                </div>

                <div class="col-lg-3">
                    <x-card.card-basic title="Sedang Ujian" value="{{ $totalUjian }}" subValue="Santri"
                        iconClass="bi bi-file-text" textColor="purple" />
                </div>

                <div class="col-lg-3">
                    <x-card.card-basic title="Sedang Wawancara" value="{{ $totalWawancara }}" subValue="Santri"
                        iconClass="bi bi-person-check" textColor="purple" />
                </div>
            </div>

            <div style="gap: 5rem;" class="d-flex flex-column">
                <div wire:ignore class="row">
                    <div class="col-lg-8">
                        <h4 class="mb-3">Statistik Pendaftar</h4>
                        <div id="pendaftarChart" class="card rounded-4 p-4 h-100 w-100"></div>
                        
                    </div>
                    <div class="col-lg-4">
                        <h4 class="mb-3">Status Pendaftaran</h4>
                        <div id="statusChart" class="card rounded-4 p-4 h-100 w-100">
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <x-card.card-basic title="Santri Diterima" value="{{ $totalDiterima }}" subValue="Santri"
                                        iconClass="bi bi-check-circle-fill" textColor="purple" />
                                </div>

                                <div class="col-lg-6">
                                    <x-card.card-basic title="Santri Ditolak" value="{{ $totalDitolak }}" subValue="Santri"
                                        iconClass="bi bi-x-circle-fill" textColor="purple" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-santri">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-3">Pendaftar Terbaru</h4>
                        <a style="text-decoration: underline;" class="text-dark" wire:navigate
                            href="{{ route('admin.master-psb.show-registrations') }}">Lihat semua pendaftar</a>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>Program</th>
                                            <th>Status</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentRegistrations as $santri)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $santri->nama_lengkap }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $santri->tipe_pendaftaran == 'reguler' ? 'primary' : ($santri->tipe_pendaftaran == 'olimpiade' ? 'warning' : 'info') }}">
                                                        {{ ucfirst($santri->tipe_pendaftaran) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $santri->status_santri == 'menunggu' ? 'warning' : ($santri->status_santri == 'diterima' ? 'success' : 'danger') }}">
                                                        {{ ucfirst(str_replace('_', ' ', $santri->status_santri)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $santri->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.master-psb.detail-registration', ['santriId' => $santri->id]) }}"
                                                        wire:navigate>
                                                        <button class="btn btn-sm btn-primary text-white">
                                                            <i class="bi bi-eye-fill"></i> Detail
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Bar Chart (Pendaftar Chart)
            var pendaftarOptions = {
                series: [{
                    name: 'Laki-laki',
                    data: [{{ $pendaftarByGender->where('jenis_kelamin', 'L')->sum('total') }}]
                }, {
                    name: 'Perempuan',
                    data: [{{ $pendaftarByGender->where('jenis_kelamin', 'P')->sum('total') }}]
                }],
                chart: {
                    type: 'bar',
                    height: 400,
                    stacked: false,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                    },
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#1c1dab', '#e893c5'],
                xaxis: {
                    categories: ['Jenis Kelamin'],
                },
                legend: {
                    position: 'top',
                    fontSize: '14px',
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }]
            };

            var pendaftarChart = new ApexCharts(document.querySelector("#pendaftarChart"), pendaftarOptions);
            pendaftarChart.render();

            // Pie Chart (Status Chart)
            var statusOptions = {
                series: [
                    {{ $pendaftarByStatus->where('status_santri', 'menunggu')->sum('total') }},
                    {{ $pendaftarByStatus->where('status_santri', 'wawancara')->sum('total') }},
                    {{ $pendaftarByStatus->where('status_santri', 'sedang_ujian')->sum('total') }},
                    {{ $pendaftarByStatus->where('status_santri', 'diterima')->sum('total') }},
                    {{ $pendaftarByStatus->where('status_santri', 'ditolak')->sum('total') }}
                ],
                chart: {
                    type: 'pie',
                    height: 400
                },
                labels: ['Menunggu', 'Wawancara', 'Sedang Ujian', 'Diterima', 'Ditolak'],
                colors: ['#ffc107', '#17a2b8', '#007bff', '#28a745', '#dc3545'],
                legend: {
                    position: 'bottom',
                    fontSize: '14px',
                },
                title: {
                    text: 'Status Pendaftaran',
                    align: 'center',
                    style: {
                        fontSize: '16px'
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 280
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var statusChart = new ApexCharts(document.querySelector("#statusChart"), statusOptions);
            statusChart.render();
        </script>
    </div>
        </div>
