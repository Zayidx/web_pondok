<div>
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                
                <p class="text-subtitle text-muted">Ringkasan profil dan riwayat ujian santri.</p>
            </div>
            
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="avatar avatar-xl mb-3">
                             <span class="avatar-content bg-primary rounded-circle fs-3">{{ substr($santri->nama_lengkap, 0, 1) }}</span>
                        </div>
                        <h4 class="card-title">{{ $santri->nama_lengkap }}</h4>
                        <p class="text-muted">NISN: {{ $santri->nisn }}</p>
                        <hr>
                        <p class="mb-2">Status Pendaftaran:</p>
                        <span class="badge fs-6 bg-light-{{ $santri->status_santri === 'sedang_ujian' ? 'warning' : 'success' }}">
                            {{ ucfirst(str_replace('_', ' ', $santri->status_santri)) }}
                        </span>
                    </div>
                </div>

                <div class="card mb-4">
                     <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-content bg-light-info rounded-3 fs-4">
                                    <i class="bi bi-bar-chart-line-fill text-info"></i>
                                </span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Total Nilai</h6>
                                    <small class="text-muted">Semua Ujian</small>
                                </div>
                                <h5 class="mb-0">{{ number_format($totalNilai, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                   <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-content bg-light-success rounded-3 fs-4">
                                    <i class="bi bi-check2-circle text-success"></i>
                                </span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Rata-rata Nilai</h6>
                                    <small class="text-muted">Keseluruhan</small>
                                </div>
                                <h5 class="mb-0 fw-bold">{{ number_format($rataRata, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Riwayat Ujian</h4>
                        <div class="input-group w-50" style="max-width: 250px;">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari ujian...">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th wire:click="sortBy('nama_ujian')" style="cursor: pointer;">
                                            Nama Ujian
                                            <span class="float-end">
                                                @if($sortField === 'nama_ujian')
                                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </span>
                                        </th>
                                        <th>Mata Pelajaran</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ujianList as $ujian)
                                        @php
                                            $hasilUjian = $ujian->hasilUjians->first();
                                            $status = $hasilUjian ? $hasilUjian->status : 'belum_mulai';
                                            $nilai = $hasilUjian ? $hasilUjian->nilai_akhir : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $ujian->nama_ujian }}</div>
                                                <small class="text-muted">{{ $ujian->tanggal_ujian->format('d M Y') }}</small>
                                            </td>
                                            <td>{{ $ujian->mata_pelajaran }}</td>
                                            <td class="text-center fw-bold">{{ number_format($nilai, 2) }}</td>
                                            <td class="text-center">
                                                @if($hasilUjian)
                                                    <a href="{{ route('admin.psb.ujian.detail-soal', ['ujianId' => $ujian->id, 'santriId' => $santri->id]) }}" 
                                                       class="btn btn-sm btn-outline-primary" wire:navigate>
                                                        <i class="bi bi-eye"></i> Detail
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="text-center py-5">
                                                    <i class="bi bi-file-earmark-x" style="font-size: 3rem;"></i>
                                                    <p class="mt-2">Tidak ada riwayat ujian yang ditemukan.</p>
                                                </div>
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
            document.addEventListener('livewire:load', function () {
                const ujianData = @json($ujianList->map(function($ujian) {
                    return [
                        'nama' => $ujian->nama_ujian,
                        'nilai' => $ujian->hasilUjians->first()->nilai_akhir ?? 0
                    ];
                }));

                const chartCategories = ujianData.map(item => item.nama);
                const chartSeriesData = ujianData.map(item => item.nilai);

                var options = {
                    series: [{
                        name: 'Nilai',
                        data: chartSeriesData
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '45%',
                            distributed: true,
                            borderRadius: 4,
                        },
                    },
                    dataLabels: {
                        enabled: true
                    },
                    legend: {
                        show: false
                    },
                    xaxis: {
                        categories: chartCategories,
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        max: 100
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " poin"
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#nilaiChart"), options);
                chart.render();
            });
        </script>
    @endpush

    <style>
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .avatar.avatar-xl {
            width: 80px;
            height: 80px;
            font-size: 2.5rem;
        }
        .avatar-content {
            font-size: 1.2rem;
        }
    </style>
</div>