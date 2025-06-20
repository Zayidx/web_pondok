<div>
    <div class="card shadow-sm rounded-4 border-light-subtle">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-file-earmark-arrow-down-fill fs-5 text-primary"></i>
                <div class="ms-3">
                    <h5 class="card-title mb-0">Generator Laporan</h5>
                    <p class="text-muted mb-0 small">Pilih jenis laporan yang ingin Anda unduh.</p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill" id="reportTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-3" id="harian-tab" data-bs-toggle="tab" data-bs-target="#harian-tab-pane" type="button" role="tab" aria-controls="harian-tab-pane" aria-selected="true">
                        <i class="bi bi-calendar-day me-2"></i>Laporan Harian
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-3" id="rekap-tab" data-bs-toggle="tab" data-bs-target="#rekap-tab-pane" type="button" role="tab" aria-controls="rekap-tab-pane" aria-selected="false">
                        <i class="bi bi-archive-fill me-2"></i>Laporan Rekapitulasi
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="reportTabContent">
                <div class="tab-pane fade show active p-4" id="harian-tab-pane" role="tabpanel" aria-labelledby="harian-tab" tabindex="0">
                    <p class="text-muted">Unduh rincian kehadiran santri berdasarkan tanggal yang dipilih. Laporan ini akan menampilkan status kehadiran untuk setiap mata pelajaran pada hari tersebut.</p>
                    <div class="row align-items-end">
                        <div class="col-md-7">
                            <label for="selectedDate" class="form-label">Pilih Tanggal</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" class="form-control" id="selectedDate" wire:model.lazy="selectedDate">
                            </div>
                        </div>
                        <div class="col-md-5 mt-3 mt-md-0">
                            <button wire:click="exportHarian" wire:loading.attr="disabled" class="btn btn-primary w-100">
                                <span wire:loading.remove wire:target="exportHarian">
                                    <i class="bi bi-download me-2"></i>Unduh Laporan Harian
                                </span>
                                <span wire:loading wire:target="exportHarian">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade p-4" id="rekap-tab-pane" role="tabpanel" aria-labelledby="rekap-tab" tabindex="0">
                    <div class="mb-4">
                        <label for="filterKelas" class="form-label">Filter Berdasarkan Kelas (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-filter-circle"></i></span>
                            <select class="form-select" id="filterKelas" wire:model="filterKelasId">
                                 <option value="">Semua Kelas</option>
                                 @foreach($listKelas as $kelas)
                                     <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                                 @endforeach
                            </select>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                            <div class="col-md-6">
                                <label for="selectedMonth" class="form-label">Rekap Bulanan</label>
                                <input type="month" class="form-control" id="selectedMonth" wire:model.lazy="selectedMonth">
                            </div>
                            <div class="col-md-4">
                                <button wire:click="exportBulanan" wire:loading.attr="disabled" class="btn btn-sm btn-info w-100 text-white">
                                    <span wire:loading.remove wire:target="exportBulanan"><i class="bi bi-download me-2"></i>Unduh Bulanan</span>
                                    <span wire:loading wire:target="exportBulanan">Memproses...</span>
                                </button>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                           <div class="col-md-6">
                                <label for="selectedYear" class="form-label">Rekap Tahunan</label>
                                <input type="number" class="form-control" id="selectedYear" wire:model.lazy="selectedYear" min="2020" max="2100" placeholder="Contoh: {{ now()->year }}">
                            </div>
                            <div class="col-md-4">
                                <button wire:click="exportTahunan" wire:loading.attr="disabled" class="btn btn-sm btn-warning w-100 text-dark">
                                    <span wire:loading.remove wire:target="exportTahunan"><i class="bi bi-download me-2"></i>Unduh Tahunan</span>
                                    <span wire:loading wire:target="exportTahunan">Memproses...</span>
                                </button>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                           <div>
                                <h6 class="mb-0">Rekap Total</h6>
                                <p class="small text-muted mb-0">Unduh rekapitulasi dari seluruh data absensi yang ada.</p>
                           </div>
                            <div class="col-md-4">
                                <button wire:click="exportSeluruh" wire:loading.attr="disabled" class="btn btn-sm btn-secondary w-100">
                                    <span wire:loading.remove wire:target="exportSeluruh"><i class="bi bi-download me-2"></i>Unduh Keseluruhan</span>
                                    <span wire:loading wire:target="exportSeluruh">Memproses...</span>
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>