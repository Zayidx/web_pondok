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

        <div class="row mb-5" wire:ignore>
            <div class="col-6">
                <h4 class="mb-3">Perbandingan Kehadiran Santri per Kelas</h4>
                <div id="kehadiranPerKelasChart" class="card rounded-4 p-4 w-100"></div>
            </div>
            <div class="table-jadwal col-6">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-3">Jadwal Piket: {{ $hariDipilih }}, {{ $tanggalDipilihFormatted }}</h4>
                </div>
                <div class="card rounded-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kelas</th>
                                        <th>Total Mapel</th>
                                        <th>Jadwal Masuk</th>
                                        <th>Jadwal Pulang</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($groupedJadwal as $kelasId => $dataKelas)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $dataKelas['kelas_nama'] }}</td>
                                        <td class="text-center"><span class="badge bg-primary">{{ $dataKelas['total_mapel'] }} Mapel</span></td>
                                        <td class="text-center">{{ $dataKelas['jadwal_masuk'] }}</td>
                                        <td class="text-center">{{ $dataKelas['jadwal_pulang'] }}</td>
                                        <td class="text-center">
                                            <a wire:navigate href="{{ route('admin.piket.detail_kelas', ['kelasId' => $dataKelas['kelas_id'], 'tanggal' => $selectedDate]) }}"
                                                wire:navigate class="btn btn-sm btn-primary text-white">
                                                <i class="bi bi-eye-fill"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
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
        document.addEventListener('livewire:navigated', () => {
            const chartLabels = @json($chartLabels);
            const chartData = @json($chartData);

            var kehadiranOptions = {
                series: [{
                    name: 'Jumlah Hadir',
                    data: chartData
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: true
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
                        text: 'Jumlah Santri Hadir'
                    }
                },
                fill: {
                    opacity: 1
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
                // Hapus instance chart sebelumnya jika ada untuk mencegah duplikasi
                if (window.kehadiranChart instanceof ApexCharts) {
                    window.kehadiranChart.destroy();
                }
                window.kehadiranChart = new ApexCharts(chartEl, kehadiranOptions);
                window.kehadiranChart.render();
            }
        });
    </script>
    @endpush
</div>