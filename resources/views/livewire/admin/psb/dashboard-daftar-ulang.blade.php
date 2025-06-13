<div>
    {{-- Notifikasi --}}
    {{-- Blok ini berfungsi untuk menampilkan pesan notifikasi 'success' yang disimpan dalam sesi. --}}
    @if (session()->has('success'))
    {{-- Direktif Blade ini memeriksa apakah ada pesan dengan kunci 'success' di dalam session (misalnya, setelah operasi berhasil). --}}
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{-- Ini adalah elemen div untuk menampilkan alert (notifikasi) dari Bootstrap. --}}
        {{-- `alert-success`: Memberikan warna hijau untuk indikasi sukses. --}}
        {{-- `alert-dismissible`: Memungkinkan alert untuk ditutup. --}}
        {{-- `fade show`: Menambahkan efek transisi saat alert muncul. --}}
        {{ session('success') }}
        {{-- Menampilkan isi pesan 'success' dari session. --}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        {{-- Tombol untuk menutup alert. `data-bs-dismiss="alert"` adalah atribut Bootstrap yang mengaktifkan fungsionalitas penutupan. --}}
        {{-- `aria-label="Close"`: Penting untuk aksesibilitas, memberikan deskripsi tombol untuk screen reader. --}}
    </div>
    @endif

    {{-- Judul Halaman --}}
    {{-- Bagian ini mendefinisikan judul dan subjudul halaman. --}}
    <div class="page-title">
        {{-- Kontainer utama untuk bagian judul halaman, mungkin memiliki styling CSS kustom. --}}
        <div class="row">
            {{-- Menggunakan sistem grid Bootstrap untuk tata letak. --}}
            <div class="col-12 col-md-6 order-md-1 order-last">
                {{-- Kolom ini akan mengambil 12 kolom pada layar sangat kecil (default) dan 6 kolom pada layar medium ke atas. --}}
                {{-- `order-md-1 order-last`: Mengatur urutan kolom pada breakpoint yang berbeda. --}}
                
                <p class="text-subtitle text-muted">Manajemen verifikasi pendaftaran ulang santri baru.</p>
                {{-- Paragraf ini berfungsi sebagai subjudul atau deskripsi halaman. --}}
                {{-- `text-subtitle` dan `text-muted` adalah kelas Bootstrap untuk styling teks (warna abu-abu, ukuran lebih kecil). --}}
            </div>
        </div>
    </div>
    
    {{-- Kartu Utama --}}
    {{-- Kartu ini berisi daftar pendaftar ulang dan fitur-fitur terkait seperti pencarian dan filter. --}}
    <div class="card">
        {{-- Elemen `card` adalah komponen UI Bootstrap yang mengelompokkan konten secara visual dengan border dan shadow. --}}
        <div class="card-header">
            {{-- Header dari kartu. --}}
            <h4>Daftar Pendaftar Ulang</h4>
            {{-- Judul untuk konten di dalam kartu. --}}
        </div>
        <div class="card-body">
            {{-- Isi dari kartu. --}}
            <div class="row mb-4 g-2">
                {{-- Baris Bootstrap untuk menampung elemen-elemen filter. --}}
                {{-- `mb-4`: Menambahkan margin bawah 4 unit. --}}
                {{-- `g-2`: Menambahkan gutter (jarak) 2 unit di antara kolom. --}}
                <div class="col-md-5">
                    {{-- Kolom untuk input pencarian, mengambil 5 kolom pada layar medium ke atas. --}}
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari berdasarkan nama atau NISN...">
                    {{-- Input teks untuk pencarian. --}}
                    {{-- `wire:model.live.debounce.300ms="search"`: Ini adalah fitur Livewire. --}}
                    {{--   - `wire:model.live`: Secara otomatis mengikat nilai input ke properti `search` di komponen Livewire, dan mengirimkan perubahan ke server secara real-time. --}}
                    {{--   - `.debounce.300ms`: Menunda pengiriman perubahan ke server selama 300 milidetik setelah user berhenti mengetik. Ini mencegah terlalu banyak request saat user mengetik cepat. --}}
                    {{-- `form-control`: Kelas Bootstrap untuk styling input form. --}}
                    {{-- `placeholder`: Teks petunjuk dalam input. --}}
                </div>
                <div class="col-md-3">
                    {{-- Kolom untuk filter 'tipe', mengambil 3 kolom pada layar medium ke atas. --}}
                    <select wire:model.live="filters.tipe" class="form-select">
                        {{-- Dropdown (select) untuk filter berdasarkan tipe. --}}
                        {{-- `wire:model.live="filters.tipe"`: Mengikat nilai pilihan ke properti `filters.tipe` di komponen Livewire secara real-time. --}}
                        {{-- `form-select`: Kelas Bootstrap untuk styling dropdown. --}}
                        @foreach($this->tipeOptions as $value => $label)
                            {{-- Mengulang array `$this->tipeOptions` yang disediakan oleh komponen Livewire untuk mengisi opsi dropdown. --}}
                            <option value="{{ $value }}">{{ $label }}</option>
                            {{-- Setiap elemen array menjadi sebuah opsi dalam dropdown. --}}
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    {{-- Kolom untuk filter 'status', mengambil 2 kolom pada layar medium ke atas. --}}
                    <select wire:model.live="filters.status" class="form-select">
                        {{-- Dropdown (select) untuk filter berdasarkan status pembayaran. --}}
                        {{-- `wire:model.live="filters.status"`: Mengikat nilai pilihan ke properti `filters.status` di komponen Livewire secara real-time. --}}
                        @foreach($this->statusPaymentOptions as $value => $label)
                            {{-- Mengulang array `$this->statusPaymentOptions` yang disediakan oleh komponen Livewire. --}}
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    {{-- Kolom untuk tombol reset filter, mengambil 2 kolom pada layar medium ke atas. --}}
                    <button wire:click="resetFilters" class="btn btn-secondary">
                        {{-- Tombol untuk mereset semua filter. --}}
                        {{-- `wire:click="resetFilters"`: Memanggil metode `resetFilters` di komponen Livewire saat tombol diklik. --}}
                        {{-- `btn btn-secondary`: Kelas Bootstrap untuk styling tombol sekunder. --}}
                        {{-- `w-100`: Membuat tombol mengambil lebar penuh kolomnya. --}}
                        <i class="bi bi-x-circle"></i> Reset Filter
                        {{-- Menggunakan ikon "x-circle" dari Bootstrap Icons (`bi`). --}}
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                {{-- Div ini membuat tabel responsif, yang berarti tabel akan bisa di-scroll secara horizontal di layar kecil. --}}
                <table class="table table-hover table-striped">
                    {{-- Elemen tabel HTML. --}}
                    {{-- `table`: Kelas dasar Bootstrap untuk tabel. --}}
                    {{-- `table-hover`: Menambahkan efek hover pada baris tabel. --}}
                    {{-- `table-striped`: Memberikan efek garis-garis pada baris tabel (warna selang-seling) untuk keterbacaan. --}}
                    <thead>
                        {{-- Bagian header tabel. --}}
                        <tr>
                            {{-- Baris header tabel. --}}
                            {{-- Add sortBy method to table headers --}}
                            {{-- Komentar ini mengindikasikan fungsi sorting pada header tabel. --}}
                            <th wire:click="sortBy('nama_lengkap')" style="cursor: pointer;">Nama Lengkap
                                {{-- Header kolom 'Nama Lengkap'. --}}
                                {{-- `wire:click="sortBy('nama_lengkap')"`: Ketika header ini diklik, metode `sortBy` di komponen Livewire akan dipanggil dengan parameter 'nama_lengkap', memungkinkan sorting data. --}}
                                {{-- `style="cursor: pointer;"`: Mengubah kursor menjadi pointer saat diarahkan ke header, memberikan indikasi bahwa itu bisa diklik. --}}
                                @if ($sortField === 'nama_lengkap')
                                    {{-- Memeriksa apakah kolom ini adalah kolom yang sedang di-sortir. --}}
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    {{-- Menampilkan ikon panah naik atau turun dari Bootstrap Icons (`bi`) sesuai dengan arah sorting (ascending atau descending). --}}
                                @endif
                            </th>
                            <th wire:click="sortBy('nisn')" style="cursor: pointer;">NISN
                                {{-- Header kolom 'NISN' dengan fungsionalitas sorting yang sama. --}}
                                @if ($sortField === 'nisn')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('created_at')" style="cursor: pointer;">Tanggal Daftar
                                {{-- Header kolom 'Tanggal Daftar' dengan fungsionalitas sorting yang sama. --}}
                                @if ($sortField === 'created_at')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </th>
                            <th>Status Pembayaran</th>
                            {{-- Header kolom 'Status Pembayaran' (tidak bisa disortir dari sini). --}}
                            <th class="text-center">Action</th>
                            {{-- Header kolom 'Action' untuk tombol-tombol aksi, teksnya di tengah. --}}
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Bagian body tabel, tempat data akan ditampilkan. --}}
                        @forelse($registrations as $registration)
                            {{-- Direktif Blade `@forelse` mengulang `$registrations`. Jika `$registrations` kosong, blok `@empty` akan dieksekusi. --}}
                            <tr>
                                {{-- Baris data untuk setiap pendaftaran. --}}
                                <td>{{ $registration->nama_lengkap }}</td>
                                {{-- Menampilkan nama lengkap santri. --}}
                                <td>{{ $registration->nisn }}</td>
                                {{-- Menampilkan NISN santri. --}}
                                <td>{{ $registration->created_at->format('d F Y') }}</td>
                                {{-- Menampilkan tanggal pendaftaran, diformat agar lebih mudah dibaca (misalnya, 01 Januari 2023). --}}
                                <td>
                                    {{-- Kolom status pembayaran. --}}
                                    {{-- Use the new 'no_proof' status for better clarity --}}
                                    {{-- Komentar ini menunjukkan penanganan status 'belum ada bukti' untuk kejelasan. --}}
                                    @if(is_null($registration->bukti_pembayaran))
                                        {{-- Memeriksa apakah `bukti_pembayaran` null (belum ada bukti diupload). --}}
                                        <span class="badge bg-warning">Menunggu Bukti</span>
                                        {{-- Menampilkan badge kuning "Menunggu Bukti". --}}
                                    @elseif($registration->status_pembayaran === 'verified')
                                        {{-- Jika status pembayaran 'verified'. --}}
                                        <span class="badge bg-success">Terverifikasi</span>
                                        {{-- Menampilkan badge hijau "Terverifikasi". --}}
                                    @elseif($registration->status_pembayaran === 'rejected')
                                        {{-- Jika status pembayaran 'rejected'. --}}
                                        <span class="badge bg-danger">Ditolak</span>
                                        {{-- Menampilkan badge merah "Ditolak". --}}
                                    @else {{-- This will cover 'pending' status --}}
                                        {{-- Jika tidak ada kondisi di atas yang terpenuhi, berarti status adalah 'pending'. --}}
                                        <span class="badge bg-info">Menunggu Verifikasi</span>
                                        {{-- Menampilkan badge biru "Menunggu Verifikasi". --}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.psb.detail-pendaftaran', $registration->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Detail Pendaftaran">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @if($registration->bukti_pembayaran)
                                            <a href="{{ route('admin.psb.lihat-bukti', $registration->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Lihat Bukti Pembayaran"
                                               target="_blank">
                                                <i class="bi bi-file-earmark-image"></i>
                                            </a>
                                        @if($registration->status_pembayaran === 'pending' || $registration->status_pembayaran === 'rejected')
                                            <button wire:click="verifyRegistration({{ $registration->id }})"
                                                    wire:confirm="Anda yakin ingin memverifikasi pendaftaran ulang santri ini?"
                                                        class="btn btn-sm btn-success" 
                                                        title="Verifikasi Pembayaran">
                                                    <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button wire:click="rejectRegistration({{ $registration->id }})"
                                                    wire:confirm="Anda yakin ingin menolak bukti pembayaran ini? Santri harus mengupload ulang."
                                                        class="btn btn-sm btn-danger" 
                                                        title="Tolak Pembayaran">
                                                    <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    @endif
                                        <button wire:click='edit("{{ $registration->id }}")' 
                                                data-bs-toggle="modal"
                                                data-bs-target="#editPeriode" 
                                                class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            {{-- Blok ini dieksekusi jika koleksi `$registrations` kosong. --}}
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data pendaftaran ulang.</td>
                                {{-- Menampilkan pesan "Tidak ada data" yang merentang di seluruh kolom tabel. --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
            <div class="mx-3">
                {{-- Div ini memberikan margin horizontal. --}}
                {{ $registrations->links() }}
                {{-- Ini adalah helper Blade untuk menampilkan link paginasi (halaman) dari koleksi `$registrations`. --}}
                {{-- Ini bekerja jika `$registrations` adalah instance dari Laravel's paginator. --}}
            </div>
        </div>
    </div>

    {{-- Modal Detail Pendaftar Ulang --}}
    {{-- Bagian ini mendefinisikan modal (pop-up) untuk menampilkan detail pendaftar ulang. --}}
    @if($showDetailModal && $selectedRegistration)
        {{-- Modal ini hanya ditampilkan jika properti Livewire `$showDetailModal` bernilai `true` DAN `$selectedRegistration` (data pendaftaran yang dipilih) tidak kosong. --}}
        <div class="modal fade show" tabindex="-1" style="display: block;">
            {{-- Elemen dasar modal Bootstrap. --}}
            {{-- `fade show`: Untuk animasi modal muncul. --}}
            {{-- `tabindex="-1"`: Membuat modal fokusable tapi tidak bisa dijangkau oleh keyboard tabbing biasa. --}}
            {{-- `style="display: block;"`: Diperlukan agar modal terlihat saat dikelola oleh Livewire, karena Bootstrap biasanya mengontrol ini dengan JavaScript. --}}
            <div class="modal-dialog modal-lg">
                {{-- Kontainer dialog modal. `modal-lg` membuat modal lebih besar. --}}
                <div class="modal-content">
                    {{-- Konten aktual dari modal. --}}
                    <div class="modal-header">
                        {{-- Header modal. --}}
                        <h5 class="modal-title">Detail Pendaftar Ulang</h5>
                        {{-- Judul modal. --}}
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                        {{-- Tombol untuk menutup modal. `wire:click="closeModal"` akan memanggil metode `closeModal` di komponen Livewire untuk menyembunyikan modal. --}}
                    </div>
                    <div class="modal-body">
                        {{-- Body (isi) dari modal. --}}
                        <div class="row">
                            {{-- Menggunakan sistem grid untuk tata letak detail. --}}
                            <div class="col-md-6">
                                {{-- Kolom untuk data santri. --}}
                                <h6>Data Santri</h6>
                                <table class="table table-borderless table-sm">
                                    {{-- Tabel tanpa border, ukuran kecil. --}}
                                    
                                    <tr>
                                        <td class="fw-bold">Nama Lengkap</td>
                                        {{-- Label field dengan teks tebal. --}}
                                        <td>: {{ $selectedRegistration->nama_lengkap }}</td>
                                        {{-- Menampilkan nama lengkap dari `$selectedRegistration`. --}}
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">NISN</td>
                                        <td>: {{ $selectedRegistration->nisn }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Asal Sekolah</td>
                                        <td>: {{ $selectedRegistration->asal_sekolah }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                {{-- Kolom untuk data pembayaran. --}}
                                <h6>Data Pembayaran</h6>
                                <table class="table table-borderless table-sm">
                                    @if($selectedRegistration->bukti_pembayaran)
                                        {{-- Bagian ini hanya ditampilkan jika ada `bukti_pembayaran` untuk pendaftaran yang dipilih. --}}
                                        <tr>
                                            <td class="fw-bold">Bank Pengirim</td>
                                            <td>: {{ $selectedRegistration->bank_pengirim }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Nama Pengirim</td>
                                            <td>: {{ $selectedRegistration->nama_pengirim }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Tgl. Pembayaran</td>
                                            <td>: {{ optional($selectedRegistration->tanggal_pembayaran)->format('d F Y') }}</td>
                                            {{-- Menampilkan tanggal pembayaran. `optional()` digunakan untuk mencegah error jika `tanggal_pembayaran` mungkin null. --}}
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status</td>
                                            <td>: 
                                                @if($selectedRegistration->status_pembayaran === 'verified')
                                                    <span class="badge bg-success">Terverifikasi</span>
                                                @else
                                                    <span class="badge bg-info">Menunggu Verifikasi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @else
                                        {{-- Bagian ini ditampilkan jika tidak ada `bukti_pembayaran`. --}}
                                        <tr>
                                            <td><span class="text-warning">Belum ada data pembayaran.</span></td>
                                            {{-- Pesan yang menunjukkan bahwa data pembayaran belum ada. --}}
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- Footer modal, biasanya berisi tombol aksi. --}}
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Tutup</button>
                        {{-- Tombol 'Tutup' yang juga memanggil `closeModal` di Livewire. --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
        {{-- Ini adalah overlay gelap yang muncul di belakang modal, menciptakan efek fokus. --}}
        {{-- Diperlukan untuk ditambahkan secara manual ketika mengelola modal dengan Livewire. --}}
    @endif

    {{-- Modal Bukti Pembayaran --}}
    {{-- Bagian ini mendefinisikan modal untuk menampilkan gambar bukti pembayaran. --}}
    @if($showProofModal)
        {{-- Modal ini hanya ditampilkan jika properti Livewire `$showProofModal` bernilai `true`. --}}
        <div class="modal fade show" style="display: block;">
            {{-- Elemen dasar modal Bootstrap, serupa dengan modal detail. --}}
            <div class="modal-dialog modal-lg modal-dialog-centered">
                {{-- Kontainer dialog modal. `modal-lg` (large) dan `modal-dialog-centered` (membuat modal muncul di tengah layar secara vertikal). --}}
                <div class="modal-content">
                    {{-- Konten aktual dari modal bukti pembayaran. --}}
                    <div class="modal-header">
                        {{-- Header modal. --}}
                        <h5 class="modal-title">Bukti Pembayaran</h5>
                        {{-- Judul modal. --}}
                        <button type="button" class="btn-close" wire:click="closeProofModal"></button>
                        {{-- Tombol tutup modal, memanggil `closeProofModal` di Livewire. --}}
                    </div>
                    <div class="modal-body text-center">
                        {{-- Body modal, teksnya di tengah. --}}
                        <img src="{{ $proofImageUrl }}" class="img-fluid rounded" alt="Bukti Pembayaran">
                        {{-- Menampilkan gambar bukti pembayaran. --}}
                        {{-- `src="{{ $proofImageUrl }}"`: Sumber gambar diambil dari properti `$proofImageUrl` di komponen Livewire. --}}
                        {{-- `img-fluid`: Membuat gambar responsif (mengambil lebar penuh container dan mempertahankan rasio aspek). --}}
                        {{-- `rounded`: Memberikan sudut membulat pada gambar. --}}
                        {{-- `alt="Bukti Pembayaran"`: Teks alternatif untuk gambar, penting untuk aksesibilitas dan SEO. --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
        {{-- Overlay gelap untuk modal bukti pembayaran. --}}
    @endif

    {{-- Modal Notifikasi Data Kosong --}}
    @if($registrations->isEmpty())
        <div class="modal fade show" tabindex="-1" style="display: block;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Informasi</h5>
                        <button type="button" class="btn-close" wire:click="$set('showEmptyModal', false)"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="bi bi-inbox text-muted display-4"></i>
                        <p class="mt-3">Belum ada data pendaftaran ulang yang tersedia.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showEmptyModal', false)">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
```