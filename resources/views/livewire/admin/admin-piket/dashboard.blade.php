<div>
    <section>
        <div class="card rounded-4 mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center">
                    <h2 class="mb-3 mb-sm-0">Dashboard Admin Piket</h2>
                    <div class="d-flex align-items-center gap-2">
                        <label for="date-filter" class="form-label mb-0 fw-semibold">Pilih Tanggal:</label>
                        <input type="date" id="date-filter" wire:model.live="selectedDate" class="form-control" style="width: auto;">
                        <button wire:click="exportExcel" class="btn btn-success d-inline-flex align-items-center">
                            <i class="bi bi-file-earmark-excel-fill me-2"></i>
                            Export Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <x-card.card-basic title="Hadir Hari Ini" value="{{ $totalHadir ?? 0 }}" subValue="Santri"
                    iconClass="bi bi-person-check-fill" textColor="green" />
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <x-card.card-basic title="Sakit Hari Ini" value="{{ $totalSakit ?? 0 }}" subValue="Santri"
                    iconClass="bi bi-heart-pulse-fill" textColor="orange" />
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <x-card.card-basic title="Izin Hari Ini" value="{{ $totalIzin ?? 0 }}" subValue="Santri"
                    iconClass="bi bi-envelope-fill" textColor="blue" />
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <x-card.card-basic title="Alpa Hari Ini" value="{{ $totalAlpa ?? 0 }}" subValue="Santri"
                    iconClass="bi bi-person-x-fill" textColor="red" />
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12 col-lg-7 mb-4 mb-lg-0">
                <div class="card rounded-4 h-100">
                    <div class="card-body">
                        <h4 class="mb-3">Perbandingan Kehadiran Santri per Kelas</h4>
                        <div id="kehadiranPerKelasChart" wire:ignore></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="card rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-3">Jadwal Piket: {{ $hariDipilih }}, {{ $tanggalDipilihFormatted }}</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kelas</th>
                                        <th>Total Mapel</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($groupedJadwal as $kelasId => $dataKelas)
                                    <tr>
                                        <td>{{ $dataKelas['kelas_nama'] }}</td>
                                        <td><span class="badge bg-primary">{{ $dataKelas['total_mapel'] }} Mapel</span></td>
                                        <td>
                                            <a href="{{ route('admin.piket.detail_kelas', ['kelasId' => $dataKelas['kelas_id'], 'tanggal' => $selectedDate]) }}"
                                                wire:navigate class="btn btn-sm btn-primary text-white">
                                                <i class="bi bi-eye-fill"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            Tidak ada jadwal pelajaran untuk hari ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    function renderKehadiranChart() {
        const chartLabels = @json($chartLabels);
        const chartSeries = @json($chartSeries);

        if (!chartLabels || chartLabels.length === 0) {
            document.getElementById('kehadiranPerKelasChart').innerHTML = '<div class="text-center p-5">Tidak ada data untuk ditampilkan.</div>';
            return;
        }

        var kehadiranOptions = {
            series: chartSeries,
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                }
            },
            colors: ['#198754', '#fd7e14', '#0d6efd', '#dc3545'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: chartLabels,
                title: {
                    text: 'Kelas'
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah Santri'
                }
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " santri"
                    }
                }
            },
            title: {
                text: 'Grafik Kehadiran Santri per Kelas',
                align: 'center'
            }
        };

        const chartEl = document.getElementById('kehadiranPerKelasChart');
        if (chartEl) {
            if (window.kehadiranChart instanceof ApexCharts) {
                window.kehadiranChart.destroy();
            }
            window.kehadiranChart = new ApexCharts(chartEl, kehadiranOptions);
            window.kehadiranChart.render();
        }
    }

    document.addEventListener('livewire:navigated', () => {
        renderKehadiranChart();
    });

    renderKehadiranChart();

</script>
    @endpush
</div>