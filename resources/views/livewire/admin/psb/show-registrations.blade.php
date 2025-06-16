<?php /** @var \Illuminate\Support\ViewErrorBag $errors */ ?>
<div>
    <!-- Alert Messages -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif
    @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif
    </div>
{{-- Judul Halaman --}}
<div class="page-title">
        <div class="row">
        <p class="text-subtitle text-muted">Daftar santri yang menampilkan seluruh santri baru yang telah mendaftar.</p>
        </div>
    </div>
    <div class="card">
        
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Cari nama atau NISN...">
                </div>
                <div class="col-md-2">
                    <input type="text" wire:model.live="searchAlamat" class="form-control" placeholder="Cari alamat...">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filters.status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach($statusSantriOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filters.tipe" class="form-select">
                        <option value="">Semua Program</option>
                        @foreach($tipeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
        
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('nama_lengkap')" style="cursor: pointer;">
                                Nama Lengkap
                                @if ($sortField === 'nama_lengkap')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>NISN</th>
                            <th>Alamat</th>
                            <th>Program</th>
                            <th wire:click="sortBy('status_santri')" style="cursor: pointer;">
                                Status
                                @if ($sortField === 'status_santri')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registrations as $registration)
                            <tr>
                                <td>{{ $registration->nama_lengkap }}</td>
                                <td>{{ $registration->nisn }}</td>
                                <td>{{ $registration->wali->alamat ?? '-' }}</td>
                                <td>{{ $tipeOptions[$registration->tipe_pendaftaran] ?? '-' }}</td>
                                <td>
                                    @if($registration->status_santri === 'wawancara')
                                        @if($registration->tanggal_wawancara)
                                            <span class="badge bg-info">Jadwal Wawancara Sudah Keluar</span>
                                        @else
                                            <span class="badge bg-warning">Menunggu Jadwal Wawancara</span>
                                        @endif
                                    @elseif($registration->status_santri === 'sedang_ujian')
                                        <span class="badge bg-primary">Sedang Ujian</span>
                                        @elseif($registration->status_santri === 'daftar_ulang')
                                        <span class="badge bg-warning">Sedang Daftar Ulang</span>
                                    @elseif($registration->status_santri === 'diterima')
                                        <span class="badge bg-success">Diterima</span>
                                    @elseif($registration->status_santri === 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @elseif($registration->status_santri === 'menunggu')
                                        <span class="badge bg-warning">Menunggu Wawancara</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.master-psb.detail-registration', ['santriId' => $registration->id]) }}" 
                                       class="btn btn-sm btn-primary me-1">
                                        <i class="bi bi-eye"></i> Detail Santri
                                    </a>
                                    <a href="{{ route('admin.master-psb.edit-registration', ['santriId' => $registration->id]) }}" 
                                       class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    @if(!$registration->tanggal_wawancara)
                                        <button wire:click="openInterviewModal({{ $registration->id }})" 
                                                class="btn btn-sm btn-info me-1">
                                            <i class="bi bi-calendar-plus"></i> Jadwal Wawancara
                                        </button>
                                    @elseif($registration->status_santri === 'wawancara')
                                        <button wire:click="cancelInterview({{ $registration->id }})"
                                                wire:confirm="Apakah Anda yakin ingin membatalkan jadwal wawancara santri ini?"
                                                class="btn btn-sm btn-danger me-1">
                                            <i class="bi bi-calendar-x"></i> Batalkan Wawancara
                                        </button>
                                 
                                    @elseif($registration->status_santri === 'daftar_ulang')
                                        <button wire:click="cancelDaftarUlang({{ $registration->id }})"
                                                wire:confirm="Apakah Anda yakin ingin membatalkan daftar ulang santri ini? Status akan dikembalikan ke tahap diterima."
                                                class="btn btn-sm btn-danger me-1">
                                            <i class="bi bi-calendar-x"></i> Batalkan Daftar Ulang
                                        </button>
                                    @endif
                                    @if(in_array($registration->status_santri, ['diterima', 'ditolak']))
                                        <button wire:click="cancelStatus({{ $registration->id }})"
                                                wire:confirm="Apakah Anda yakin ingin membatalkan status {{ $registration->status_santri == 'diterima' ? 'penerimaan' : 'penolakan' }} santri ini?"
                                                class="btn btn-sm btn-danger me-1">
                                            <i class="bi bi-x-circle"></i> Batalkan Status
                                        </button>
                                    @endif
                                    @if($registration->status_santri === 'sedang_ujian')
                                        <button wire:click="cancelExam({{ $registration->id }})"
                                                wire:confirm="Apakah Anda yakin ingin membatalkan ujian santri ini? Status akan dikembalikan ke wawancara."
                                                class="btn btn-sm btn-danger me-1">
                                            <i class="bi bi-x-circle"></i> Batalkan Ujian
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pendaftaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
            {{ $registrations->links() }}
            </div>
        </div>
    </div>

    <!-- Interview Modal -->
    @if($showInterviewModal)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
        <div class="modal-dialog" style="z-index: 1050;">
                <div class="modal-content">
                        <div class="modal-header">
                    <h5 class="modal-title">Jadwalkan Wawancara</h5>
                    <button type="button" class="btn-close" wire:click="closeInterviewModal"></button>
                        </div>
                <form wire:submit.prevent="saveInterview">
                    <div class="modal-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        <div class="mb-3">
                            <label class="form-label">Mode Wawancara</label>
                            <select class="form-select" wire:model.live="interviewForm.mode">
                                <option value="offline">Offline (Tatap Muka)</option>
                                <option value="online">Online (Virtual)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Wawancara</label>
                            <input type="date" class="form-control" wire:model.live="interviewForm.tanggal_wawancara" min="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Waktu Wawancara</label>
                            <input type="time" class="form-control" wire:model.live="interviewForm.jam_wawancara">
                        </div>

                        @if($interviewForm['mode'] === 'online')
                        <div class="online-form">
                            <div class="mb-3">
                                <label class="form-label">Link Meeting</label>
                                <input type="url" class="form-control" wire:model.live="interviewForm.link_online" 
                                       placeholder="https://meet.google.com/...">
                                <small class="text-muted">Masukkan link Google Meet atau Zoom untuk wawancara online</small>
                            </div>
                            </div>
                        @else
                        <div class="offline-form">
                            <div class="mb-3">
                                <label class="form-label">Lokasi Wawancara</label>
                                <input type="text" class="form-control" wire:model.live="interviewForm.lokasi_offline" 
                                       placeholder="Contoh: Ruang Meeting Lt. 2">
                                <small class="text-muted">Masukkan lokasi tempat wawancara akan dilaksanakan</small>
                            </div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeInterviewModal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>