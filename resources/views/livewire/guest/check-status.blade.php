@extends('components.layouts.check-status')

@section('content')
<div>
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10" src="https://via.placeholder.com/40x40/1e40af/ffffff?text=SMA" alt="Logo SMA" />
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-bold text-primary">SMA Bina Prestasi</h1>
                        <p class="text-sm text-gray-600">Dashboard Siswa: {{ $santri->nama_lengkap ?? 'Pengguna' }}</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('santri.dashboard-ujian') }}" class="text-primary px-3 py-2 rounded-md text-sm font-medium bg-blue-50">Dashboard Ujian</a>
                        <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">Profil</a>
                        <a href="#" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">Bantuan</a>
                        <button type="button" wire:click="logout" wire:loading.attr="disabled" class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition duration-300">
                            <i class="fas fa-sign-out-alt"></i>
                            <span wire:loading.remove>Keluar</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-primary">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="md:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg">
                <a href="#" class="text-primary block px-3 py-2 rounded-md text-base font-medium bg-blue-50">Dashboard</a>
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Profil</a>
                <a href="#" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Bantuan</a>
                <button type="button" wire:click="logout" wire:loading.attr="disabled" class="w-full flex items-center gap-2 bg-red-500 text-white px-3 py-2 rounded-md text-base font-medium hover:bg-red-600 transition duration-300">
                    <i class="fas fa-sign-out-alt"></i>
                    <span wire:loading.remove>Keluar</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </div>
        </div>
    </nav>

    @if (session()->has('message'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <section class="bg-gradient-to-br from-blue-50 to-indigo-100 pt-20 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Dashboard Penerimaan Siswa Baru</h1>
                <p class="text-lg text-gray-600 mb-6">Pantau status pendaftaran dan perkembangan seleksi Anda</p>
                <div class="bg-white p-4 rounded-lg shadow-md inline-block">
                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Nomor Pendaftaran</p>
                            <p class="text-xl font-bold text-primary">{{ $santri->no_pendaftaran ?? 'PPDB2025001234' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-primary p-3 rounded-full mr-4">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Informasi Siswa</h2>
                        <p class="text-gray-600">Data pribadi pendaftar</p>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $santri->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">NISN</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $santri->nisn ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Asal Sekolah</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $santri->asal_sekolah ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Program Pilihan</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $santri->tipe_pendaftaran ? ucfirst($santri->tipe_pendaftaran) : '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Tanggal Daftar</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $santri->created_at ? $santri->created_at->format('d F Y') : '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Status Berkas</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Lengkap
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-center mb-6">
                    <div class="bg-primary p-3 rounded-full mr-4">
                        <i class="fas fa-timeline text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Timeline Seleksi</h2>
                        <p class="text-gray-600">Tahapan proses penerimaan siswa baru</p>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute left-8 top-0 bottom-0 w-1 bg-gray-300"></div>
                    <div class="space-y-8">
                        <div class="relative flex items-center">
                            <div class="bg-green-500 p-6 rounded-full text-white z-10">
                                <i class="fas fa-check text-lg"></i>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-lg font-semibold text-gray-900">Pendaftaran Online</h3>
                                <p class="text-gray-600">Formulir pendaftaran telah disubmit</p>
                                <p class="text-sm text-green-600 font-medium">✓ Selesai - {{ $timelineStatus['pendaftaran_online']['date'] }}</p>
                            </div>
                        </div>

                        <div class="relative flex items-center">
                            @php
                                $showAsCompleted = $santri->status_santri === 'daftar_ulang' || $timelineStatus['wawancara']['completed'];
                            @endphp
                            <div class="{{ $showAsCompleted ? 'bg-green-500' : ($timelineStatus['wawancara']['current'] ? 'bg-yellow-500 animate-pulse' : 'bg-gray-300') }} p-6 rounded-full text-white z-10">
                                <i class="fas {{ $showAsCompleted ? 'fa-check' : 'fa-comments' }} text-lg"></i>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-lg font-semibold {{ $showAsCompleted ? 'text-gray-900' : ($timelineStatus['wawancara']['current'] ? 'text-gray-900' : 'text-gray-500') }}">Wawancara</h3>
                                @if($showAsCompleted)
                                    <p class="text-green-600">✓ Wawancara telah selesai</p>
                                    @if($timelineStatus['wawancara']['date'])
                                        <p class="text-sm text-gray-600 font-medium">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $timelineStatus['wawancara']['date'] }}
                                        </p>
                                    @endif
                                @elseif($timelineStatus['wawancara']['date'])
                                    <p class="text-gray-600">
                                        {{ $timelineStatus['wawancara']['mode'] == 'online' ? 'Wawancara Online' : 'Wawancara Tatap Muka' }}
                                    </p>
                                    <p class="text-sm text-blue-600 font-medium">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $timelineStatus['wawancara']['date'] }} {{ $timelineStatus['wawancara']['time'] }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-{{ $timelineStatus['wawancara']['mode'] == 'online' ? 'video' : 'building' }} mr-1"></i>
                                        {{ $timelineStatus['wawancara']['location'] }}
                                    </p>
                                @else
                                    <p class="text-gray-600">Menunggu jadwal wawancara</p>
                                @endif
                            </div>
                        </div>

                        <div class="relative flex items-center">
                            @php
                                $showAsCompleted = $santri->status_santri === 'daftar_ulang' || $timelineStatus['ujian']['completed'];
                            @endphp
                            <div class="{{ $showAsCompleted ? 'bg-green-500' : ($timelineStatus['ujian']['current'] ? 'bg-yellow-500 animate-pulse' : 'bg-gray-300') }} p-6 rounded-full text-white z-10">
                                <i class="fas {{ $showAsCompleted ? 'fa-check' : 'fa-edit' }} text-lg"></i>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-lg font-semibold {{ $showAsCompleted ? 'text-gray-900' : ($timelineStatus['ujian']['current'] ? 'text-gray-900' : 'text-gray-500') }}">Ujian Online</h3>
                                @if($showAsCompleted)
                                    <p class="text-green-600">✓ Ujian telah selesai</p>
                                    @if($timelineStatus['ujian']['date'])
                                        <p class="text-sm text-gray-600 font-medium">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $timelineStatus['ujian']['date'] }}
                                        </p>
                                    @endif
                                @elseif($timelineStatus['ujian']['current'])
                                    <p class="text-gray-600">Silahkan mengikuti ujian online</p>
                                    <a href="{{ route('santri.dashboard-ujian') }}" class="inline-block mt-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition duration-300">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Menuju Dashboard Ujian
                                    </a>
                                @else
                                    <p class="text-gray-600">Menunggu jadwal ujian</p>
                                @endif
                            </div>
                        </div>

                        <div class="relative flex items-center mt-8">
                            <div class="{{ $timelineStatus['daftar_ulang']['completed'] ? 'bg-green-500' : ($timelineStatus['daftar_ulang']['current'] ? 'bg-yellow-500 animate-pulse' : 'bg-gray-300') }} p-6 rounded-full text-white z-10">
                                <i class="fas {{ $timelineStatus['daftar_ulang']['completed'] ? 'fa-check' : 'fa-clipboard-list' }} text-lg"></i>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-lg font-semibold {{ $timelineStatus['daftar_ulang']['completed'] ? 'text-gray-900' : ($timelineStatus['daftar_ulang']['current'] ? 'text-gray-900' : 'text-gray-500') }}">Daftar Ulang</h3>
                                @if($timelineStatus['daftar_ulang']['current'])
                                    @if($santri->status_pembayaran === 'rejected')
                                        <p class="text-red-600">Verifikasi pembayaran ditolak</p>
                                        @if($santri->catatan_verifikasi)
                                            <p class="text-sm text-gray-600 mb-2">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Catatan: {{ $santri->catatan_verifikasi }}
                                            </p>
                                        @endif
                                        <p class="text-gray-600">Silakan melakukan pendaftaran ulang kembali</p>
                                        <a href="{{ route('santri.daftar-ulang') }}" class="inline-block mt-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition duration-300">
                                            <i class="fas fa-redo mr-2"></i>
                                            Upload Ulang Bukti Pembayaran
                                        </a>
                                    @elseif($santri->status_pembayaran === 'pending')
                                        <p class="text-yellow-600">Menunggu verifikasi pembayaran</p>
                                        <p class="text-sm text-gray-600 font-medium">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Tanggal Upload: {{ \Carbon\Carbon::parse($santri->tanggal_pembayaran)->format('d F Y') }}
                                        </p>
                                    @else
                                        <p class="text-gray-600">Silakan melakukan pendaftaran ulang</p>
                                        <a href="{{ route('santri.daftar-ulang') }}" class="inline-block mt-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition duration-300">
                                            <i class="fas fa-clipboard-check mr-2"></i>
                                            Menuju Halaman Daftar Ulang
                                        </a>
                                    @endif
                                @elseif($timelineStatus['daftar_ulang']['completed'])
                                    <p class="text-green-600">Pendaftaran ulang telah diverifikasi</p>
                                    <p class="text-sm text-gray-600 font-medium">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $timelineStatus['daftar_ulang']['date'] }}
                                    </p>
                                @else
                                    <p class="text-gray-600">Menunggu tahap daftar ulang</p>
                                @endif
                            </div>
                        </div>

                        <div class="relative flex items-center">
                            <div class="{{ isset($timelineStatus['pengumuman_hasil']) && $timelineStatus['pengumuman_hasil']['completed'] ? 'bg-green-500' : 'bg-gray-300' }} p-6 rounded-full text-white z-10">
                                <i class="fas {{ isset($timelineStatus['pengumuman_hasil']) && $timelineStatus['pengumuman_hasil']['completed'] ? 'fa-check' : 'fa-bullhorn' }} text-lg"></i>
                            </div>
                            <div class="ml-6">
                                <h3 class="text-lg font-semibold {{ isset($timelineStatus['pengumuman_hasil']) && $timelineStatus['pengumuman_hasil']['completed'] ? 'text-gray-900' : 'text-gray-500' }}">Pengumuman Hasil</h3>
                                @if(isset($timelineStatus['pengumuman_hasil']) && $timelineStatus['pengumuman_hasil']['completed'])
                                <p class="text-{{ isset($timelineStatus['pengumuman_hasil']['status']) && $timelineStatus['pengumuman_hasil']['status'] == 'diterima' ? 'green' : 'red' }}-600">
                                    {{ isset($timelineStatus['pengumuman_hasil']['status']) && $timelineStatus['pengumuman_hasil']['status'] == 'diterima' ? 'Selamat! Anda dinyatakan DITERIMA' : 'Mohon maaf, Anda belum diterima' }}
                                </p>
                                @if(isset($timelineStatus['pengumuman_hasil']['date']))
                                <p class="text-sm text-gray-600 font-medium">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ $timelineStatus['pengumuman_hasil']['date'] }}
                                </p>
                                @endif
                                @else
                                <p class="text-gray-500">Menunggu hasil seleksi</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                @if($santri->status_santri == 'menunggu')
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-yellow-100 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Menunggu</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Menunggu Status</h3>
                    <p class="text-gray-600 mb-4">Status pendaftaran Anda masih dalam proses peninjauan. Silakan tunggu informasi lebih lanjut.</p>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-sm text-yellow-800 font-medium">
                            <i class="fas fa-info-circle mr-2"></i>
                            Pastikan Anda memeriksa email dan WhatsApp untuk update terbaru.
                        </p>
                    </div>
                @elseif($santri->status_santri == 'wawancara')
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Wawancara</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Jadwal Wawancara</h3>
                    @if($santri->tanggal_wawancara)
                        <p class="text-gray-600 mb-4">Berikut adalah detail jadwal wawancara Anda:</p>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="space-y-2">
                                <p class="text-sm text-blue-800 font-medium">
                                    <i class="fas fa-calendar mr-2"></i>
                                    Tanggal: {{ \Carbon\Carbon::parse($santri->tanggal_wawancara)->format('d F Y') }}
                                </p>
                                <p class="text-sm text-blue-800 font-medium">
                                    <i class="fas fa-clock mr-2"></i>
                                    Waktu: {{ \Carbon\Carbon::parse($santri->tanggal_wawancara)->format('H:i') }} WIB
                                </p>
                                <p class="text-sm text-blue-800 font-medium">
                                    <i class="fas fa-{{ $santri->mode == 'online' ? 'video' : 'building' }} mr-2"></i>
                                    Mode: {{ ucfirst($santri->mode) }}
                                </p>
                                @if($santri->mode == 'online')
                                    <p class="text-sm text-blue-800 font-medium">
                                        <i class="fas fa-link mr-2"></i>
                                        Link: <a href="{{ $santri->link_online }}" target="_blank" class="underline">{{ $santri->link_online }}</a>
                                    </p>
                                @else
                                    <p class="text-sm text-blue-800 font-medium">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        Lokasi: {{ $santri->lokasi_offline }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600 mb-4">Jadwal wawancara Anda akan segera diinformasikan. Harap menunggu.</p>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-sm text-yellow-800 font-medium">
                                <i class="fas fa-info-circle mr-2"></i>
                                Kami akan menghubungi Anda melalui email dan WhatsApp ketika jadwal sudah ditentukan.
                            </p>
                        </div>
                    @endif
                @elseif($santri->status_santri == 'sedang_ujian')
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-edit text-blue-600 text-2xl"></i>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Sedang Ujian</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tahap Ujian Online</h3>
                    <p class="text-gray-600 mb-4">Selamat! Anda telah lulus tahap wawancara dan dapat melanjutkan ke tahap ujian online.</p>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-800 font-medium mb-4">
                            <i class="fas fa-info-circle mr-2"></i>
                            Silahkan klik tombol di bawah untuk mengakses dashboard ujian Anda.
                        </p>
                        <a href="{{ route('santri.dashboard-ujian') }}" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition duration-300">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Menuju Dashboard Ujian
                        </a>
                    </div>
                @elseif($santri->status_santri == 'diterima')
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Diterima</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Selamat! Anda Diterima</h3>
                    <p class="text-gray-600 mb-4">Anda dinyatakan LULUS dan diterima di SMA Bina Prestasi. Silakan Download bukti penerimaan di bawah ini.</p>
                    
                    {{-- Ganti button wire:click dengan link ke komponen Livewire baru --}}
                    <a href="{{ route('psb.cetak-penerimaan', ['registrationId' => $santri->id]) }}"
                       target="_blank" {{-- Buka di tab baru --}}
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-file-pdf mr-3"></i> Unduh Surat Penerimaan (PDF)
                    </a>
                    
                    <div class="bg-green-50 p-4 rounded-lg mt-4">
                        <p class="text-sm text-green-800 font-medium">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi lengkap tentang daftar ulang akan dikirimkan melalui email dan WhatsApp.
                        </p>
                    </div>
                @elseif($santri->status_santri == 'ditolak')
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                        </div>
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">Ditolak</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Mohon Maaf</h3>
                    <p class="text-gray-600 mb-4">Anda belum berhasil lolos seleksi kali ini. Jangan menyerah, coba lagi tahun depan!</p>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm text-red-800 font-medium">
                            <i class="fas fa-heart mr-2"></i>
                            Tetap semangat untuk masa depan yang cerah!
                        </p>
                    </div>
                @elseif($santri->status_santri == 'daftar_ulang')
                    <div class="flex items-center justify-between mb-4">
                        @if($santri->status_pembayaran === 'rejected')
                            <div class="bg-red-100 p-3 rounded-full">
                                <i class="fas fa-times text-red-600 text-2xl"></i>
                            </div>
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">Ditolak</span>
                        @elseif($santri->status_pembayaran === 'pending')
                            <div class="bg-yellow-100 p-3 rounded-full">
                                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                            </div>
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Menunggu Verifikasi</span>
                        @else
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Daftar Ulang</span>
                        @endif
                    </div>
                    
                    @if($santri->status_pembayaran === 'rejected')
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Verifikasi Pembayaran Ditolak</h3>
                        <p class="text-gray-600 mb-4">Mohon maaf, pembayaran Anda tidak dapat diverifikasi. Silakan lakukan upload ulang bukti pembayaran.</p>
                        @if($santri->catatan_verifikasi)
                            <div class="bg-red-50 p-4 rounded-lg mb-4">
                                <p class="text-sm text-red-800 font-medium">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Catatan: {{ $santri->catatan_verifikasi }}
                                </p>
                            </div>
                        @endif
                        <a href="{{ route('santri.daftar-ulang') }}" class="inline-block px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition duration-300">
                            <i class="fas fa-redo mr-2"></i>
                            Upload Ulang Bukti Pembayaran
                        </a>
                    @elseif($santri->status_pembayaran === 'pending')
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Menunggu Verifikasi</h3>
                        <p class="text-gray-600 mb-4">Bukti pembayaran Anda sedang dalam proses verifikasi. Mohon tunggu informasi selanjutnya.</p>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <p class="text-sm text-yellow-800 font-medium">
                                <i class="fas fa-info-circle mr-2"></i>
                                Kami akan menginformasikan hasil verifikasi melalui email dan WhatsApp.
                            </p>
                        </div>
                    @endif
                @endif {{-- Ini adalah penutup untuk @if($santri->status_santri == 'daftar_ulang') --}}
            </div>
        </div>
    </section>

    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                    <div class="bg-blue-500 p-2 rounded-full mr-4 mt-1">
                        <i class="fas fa-info text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Informasi Penting</h3>
                        <ul class="text-blue-800 space-y-2">
                            <li>• Jadwal wawancara akan dikirim melalui email dan WhatsApp H-3 sebelum pelaksanaan</li>
                            <li>• Pastikan nomor telepon dan email Anda aktif</li>
                            <li>• Siapkan dokumen asli untuk verifikasi saat wawancara</li>
                            <li>• Wawancara dilakukan secara offline di sekolah</li>
                            <li>• Pengumuman hasil akhir pada 20 April 2025</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Butuh Bantuan?</h2>
                    <p class="text-gray-600">Tim PPDB siap membantu Anda</p>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-green-100 p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fab fa-whatsapp text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">WhatsApp</h3>
                        <p class="text-gray-600 mb-4">Chat langsung dengan tim PPDB</p>
                        <a href="https://wa.me/6281234567890" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300">Chat Sekarang</a>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Email</h3>
                        <p class="text-gray-600 mb-4">ppdb@smabinaprestasi.sch.id</p>
                        <a href="mailto:ppdb@smabinaprestasi.sch.id" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Kirim Email</a>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-phone text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Telepon</h3>
                        <p class="text-gray-600 mb-4">(021) 1234-5678</p>
                        <a href="tel:+62211234567" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition duration-300">Hubungi</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <img class="h-10 w-10 mr-3" src="https://via.placeholder.com/40x40/1e40af/ffffff?text=SMA" alt="Logo SMA" />
                    <h3 class="text-xl font-bold">SMA Bina Prestasi</h3>
                </div>
                <p class="text-gray-400 mb-4">Dashboard Penerimaan Siswa Baru Tahun Ajaran 2025/2026</p>
                <p class="text-gray-400">© 2025 SMA Bina Prestasi. All Rights Reserved.</p>
            </div>
        </div>
    </footer>


</div>

@endsection

{{-- Hapus atau Komentar bagian JavaScript ini karena sudah tidak relevan dengan alur baru --}}
{{--
@push('scripts')
<script>
    Livewire.on('downloadPdf', (data) => {
        const htmlContent = data.html;
        const fileName = data.fileName;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('download-certificate-pdf') }}';
        form.target = '_blank';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const htmlInput = document.createElement('input');
        htmlInput.type = 'hidden';
        htmlInput.name = 'html_content';
        htmlInput.value = htmlContent;
        form.appendChild(htmlInput);

        const fileNameInput = document.createElement('input');
        fileNameInput.type = 'hidden';
        fileNameInput.name = 'file_name';
        fileNameInput.value = fileName;
        form.appendChild(fileNameInput);

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    });
</script>
@endpush
--}}