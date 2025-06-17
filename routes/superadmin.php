<?php

// Import class-class yang sudah ada
use App\Livewire\Admin;
use App\Livewire\Admin\ListSantri\DetailSantri;
use App\Livewire\Admin\PSB\DashboardUjian;
use App\Livewire\Admin\PSB\DetailRegistration;
use App\Livewire\Admin\PSB\DetailUjian;
use App\Livewire\Admin\PSB\InterviewList;
use App\Livewire\Admin\PSB\PeriodeManager;
use App\Livewire\Admin\PSB\ShowRegistrations;
use App\Livewire\Admin\PSB\UjianEssay;
use App\Livewire\Admin\PSB\PreviewUjian;
use App\Livewire\Admin\PSB\HasilUjianSantri;
use App\Livewire\Admin\PSB\DetailUjianSantri;
use Illuminate\Support\Facades\Route;

// Import class-class baru untuk fitur Absensi Piket, menggunakan alias agar lebih jelas
use App\Livewire\Admin\AdminPiket\Dashboard as PiketDashboard;
use App\Livewire\Admin\AdminPiket\DetailAbsensi as PiketDetailAbsensi;
use App\Livewire\Admin\AdminPiket\AbsenMurid as PiketAbsenMurid;
use App\Livewire\Admin\AdminPiket\HasilAbsensi as PiketHasilAbsensi;

// Grup utama untuk semua rute admin dengan role 'Super Admin'
Route::prefix('admin')->middleware(['auth', 'role:Super Admin'])->group(function () {
    // Rute dashboard utama admin
    Route::get('/dashboard', Admin\Dashboard::class)->name('admin.dashboard');

    // Grup rute untuk data master pondok
    Route::prefix('master-pondok')->group(function () {
        Route::get('/jenjang', Admin\Jenjang::class)->name('admin.master-pondok.jenjang');
        Route::get('/kelas', Admin\Kelas::class)->name('admin.master-pondok.kelas');
        Route::get('/wali-kelas', Admin\WaliKelas::class)->name('admin.master-pondok.wali-kelas');
        Route::get('/kamar', Admin\Kamar::class)->name('admin.master-pondok.kamar');
        Route::get('/wali-kamar', Admin\WaliKamar::class)->name('admin.master-pondok.wali-kamar');
        Route::get('/angkatan', Admin\Angkatan::class)->name('admin.master-pondok.angkatan');
        Route::get('/semester', Admin\Semester::class)->name('admin.master-pondok.semester');
    });

    // Grup rute untuk data master santri
    Route::prefix('master-santri')->group(function () {
        Route::get('/list-santri', Admin\ListSantri::class)->name('admin.master-santri.santri');
        Route::get('/list-santri/detail-santri/{id}', DetailSantri::class)->name('admin.master-santri.detail-santri');
        Route::get('/list-wali-santri', Admin\ListWaliSantri::class)->name('admin.master-santri.wali-santri');
        Route::get('/list-santri/export', [Admin\ListSantri::class, 'export'])->name('admin.master-santri.export');
    });

    // Grup rute untuk data master admin
    Route::prefix('master-admin')->group(function () {
        Route::get('/list-admin', Admin\ListAdmin::class)->name('admin.master-admin.list-admin');
        Route::get('/list-role', Admin\ListRole::class)->name('admin.master-admin.list-role');
    });

    // Grup rute untuk data master aktivitas
    Route::prefix('master-aktifitas')->group(function () {
        Route::get('/pengumuman', Admin\Pengumuman::class)->name('admin.master-aktifitas.pengumuman');
        Route::get('/kegiatan', Admin\Kegiatan::class)->name('admin.master-aktifitas.kegiatan');
    });

    // Grup rute untuk data master PSB
    Route::prefix('master-psb')->group(function () {
        Route::get('/dashboard', \App\Livewire\SantriPPDB\SantriDashboard::class)->name('santrippdb.dashboard');
        Route::get('/registrations', ShowRegistrations::class)->name('admin.master-psb.show-registrations');
        Route::get('/registrations/{santriId}', DetailRegistration::class)->name('admin.master-psb.detail-registration');
        Route::get('/registrations/{santriId}/edit', \App\Livewire\Admin\PSB\EditRegistration::class)->name('admin.master-psb.edit-registration');
        Route::get('/wawancara-santri', InterviewList::class)->name('admin.master-psb.interview-list');
    });

    // Grup rute untuk data master periode pendaftaran
    Route::prefix('master-periode')->group(function () {
        Route::get('/dashboard', PeriodeManager::class)->name('admin.master-periode.dashboard');
    });

    // Grup rute untuk data master ujian
    Route::prefix('master-ujian')->group(function () {
        Route::get('/dashboard', DashboardUjian::class)->name('admin.master-ujian.dashboard');
        Route::get('/detail/{ujianId}', DetailUjian::class)->name('admin.master-ujian.detail');
        Route::get('/preview/{ujianId}', PreviewUjian::class)->name('admin.psb.ujian.preview');
        Route::get('/essay/{ujianId}', UjianEssay::class)->name('admin.master-ujian.essay');
    });
    
    // ==================================================================
    // == PENAMBAHAN GRUP RUTE BARU UNTUK FITUR ABSENSI PIKET ==
    // ==================================================================
    Route::prefix('master-piket')->name('admin.piket.')->group(function () {
        
        // Rute untuk dashboard piket yang menampilkan rekap jadwal per kelas
        Route::get('/dashboard', PiketDashboard::class)->name('dashboard');
        
        // Rute untuk halaman detail jadwal suatu kelas pada tanggal tertentu
        Route::get('/detail-kelas/{kelasId}/{tanggal}', PiketDetailAbsensi::class)->name('detail_kelas');
        
        // Rute untuk halaman absensi dengan QR Code (khusus hari ini)
        Route::get('/absensi/murid/{jadwalId}', PiketAbsenMurid::class)->name('absensi.murid');
        
        // Rute untuk halaman rekap hasil absensi (untuk tanggal yang telah lewat)
        Route::get('/hasil-absensi/{jadwalId}/{tanggal}', PiketHasilAbsensi::class)->name('hasil.absensi');
    });
    // ==================================================================
    // == AKHIR DARI GRUP RUTE ABSENSI PIKET ==
    // ==================================================================

    // Grup rute untuk hasil ujian santri
    Route::prefix('psb/ujian')->name('admin.psb.ujian.')->group(function () {
        Route::get('/hasil', HasilUjianSantri::class)->name('hasil');
        Route::get('/detail/{id}', DetailUjianSantri::class)->name('detail');
    });
});

// Anda juga perlu rute untuk proses scan santri di luar grup admin, contohnya:
// Route::get('/santri/absensi/scan/{token}', [NamaSantriController::class, 'handleScan'])->name('santri.absensi.scan');
// Pastikan untuk membuat Controller dan method ini untuk menangani logika saat santri scan QR.
