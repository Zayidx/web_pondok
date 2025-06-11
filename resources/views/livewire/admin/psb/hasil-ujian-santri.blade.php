<div>
    {{-- Menampilkan notifikasi sukses/error --}}
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" wire:poll.3000ms>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    @endif
    </div>

    {{-- Judul Halaman --}}
    <div class="page-title">
        <div class="row">
        <p class="text-subtitle text-muted">Daftar santri yang telah menyelesaikan ujian dan menunggu penilaian.</p>
        </div>
    </div>

    {{-- Kartu Utama dengan Filter dan Tabel --}}
    <div class="card">
      
        <div class="card-body">
            {{-- Bagian Filter dan Pencarian --}}
            <div class="row mb-4 g-2">
                <div class="col-md-3">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari Nama/NISN/Sekolah...">
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
                    <select wire:model.live="filters.nilai" class="form-select">
                        <option value="">Urutkan Nilai</option>
                        <option value="highest">Nilai Tertinggi</option>
                        <option value="lowest">Nilai Terendah</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button wire:click="resetFilters" class="btn btn-secondary  ">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </button>
                </div>
            </div>

            {{-- Tabel Hasil Ujian --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('nama_lengkap')" style="cursor: pointer;">
                                Nama Lengkap <i class="bi bi-sort-alpha-down"></i>
                            </th>
                            <th>NISN</th>
                            <th>Asal Sekolah</th>
                            <th wire:click="sortBy('rata_nilai')" style="cursor: pointer;">
                                Rata-rata Nilai <i class="bi bi-sort-numeric-down"></i>
                            </th>
                            <th>Status</th>
                            <th style="width: 250px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santriList as $santri)
                            <tr>
                                <td>{{ $santri->nama_lengkap }}</td>
                                <td>{{ $santri->nisn }}</td>
                                <td>{{ $santri->asal_sekolah }}</td>
                                <td class="fw-bold">{{ number_format($santri->rata_nilai, 2) }}</td>
                                <td>
                                    {{-- Status santri saat ini di halaman ini adalah 'sedang_ujian', yang berarti menunggu hasil. --}}
                                    <span class="badge bg-warning">Menunggu Hasil</span>
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.psb.ujian.detail', ['id' => $santri->id]) }}" 
                                       class="btn btn-sm btn-info me-1" wire:navigate>
                                        <i class="bi bi-eye"></i> Detail Ujian
                                    </a>
                                    <button wire:click="terimaSantri({{ $santri->id }})"
                                            wire:confirm="Anda yakin ingin MELULUSKAN santri ini?"
                                            class="btn btn-sm btn-success me-1">
                                        <i class="bi bi-check-circle"></i> Luluskan
                                    </button>
                                    <button wire:click="tolakSantri({{ $santri->id }})" 
                                            wire:confirm="Anda yakin ingin TIDAK MELULUSKAN santri ini?"
                                            class="btn btn-sm btn-danger me-1">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data santri yang cocok dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Link Pagination --}}
            <div class="mt-4">
                {{ $santriList->links() }}
            </div>
        </div>
    </div>
</div>