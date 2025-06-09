<?php

use App\Livewire\Admin\PSB\PsbPage;
use App\Livewire\Auth\LoginPsb;
use App\Livewire\Auth\RegisterSantri;
use App\Livewire\Guest\CheckStatus;
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

// Rute untuk tamu (tanpa autentikasi)
Route::get('/registerppdb', RegisterSantri::class)->name('register-santri');
Route::get('/loginppdbsantri', LoginPsb::class)->name('login-ppdb-santri');
Route::get('/check-status', CheckStatus::class)->name('check-status');
Route::get('/pendaftaran-santri', PsbPage::class)->name('psb-page');

// Rute untuk santri PPDB (dengan autentikasi)
Route::middleware(['santri.auth', 'check.status'])->group(function () {
   
    Route::get('/ujian-santri/{ujianId}', \App\Livewire\SantriPPDB\UjianSantri::class)->name('santri.ujian');
    Route::get('/konfirmasi-ujian/{ujianId}', \App\Livewire\SantriPPDB\KonfirmasiUjian::class)->name('santri.konfirmasi-ujian');
    Route::get('/selesai-ujian/{ujianId}', \App\Livewire\SantriPPDB\SelesaiUjian::class)->name('santri.selesai-ujian');
    Route::get('/santri-dashboard', \App\Livewire\SantriPPDB\SantriDashboard::class)->name('santri.dashboard');
    Route::get('/dashboard-ujian', \App\Livewire\SantriPPDB\DashboardUjianSantri::class)->name('santri.dashboard-ujian');
    Route::get('/riwayat-ujian', \App\Livewire\SantriPPDB\RiwayatUjian::class)->name('santri.riwayat-ujian');
});
Route::prefix('ppdb')->middleware('auth')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\PSB\Dashboard::class)->name('e-ppdb.dashboard');


    Route::prefix('master-psb')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\PSB\Dashboard::class)->name('admin.master-psb.dashboard');
        Route::get('/list-santri-baru', ShowRegistrations::class)->name('admin.master-psb.show-registrations');
        Route::get('/list-santri-baru/{santriId}', DetailRegistration::class)->name('admin.master-psb.detail-registration');
        Route::get('/list-santri-baru/{santriId}/edit', \App\Livewire\Admin\PSB\EditRegistration::class)->name('admin.master-psb.edit-registration');
        Route::get('/list-wawancara-santri', InterviewList::class)->name('admin.master-psb.interview-list');
    });
    
    Route::prefix('master-periode')->group(function () {
        Route::get('/dashboard-periode', PeriodeManager::class)->name('admin.master-periode.dashboard');
    });
    
    Route::prefix('master-ujian')->group(function () {
        Route::get('/dashboard', DashboardUjian::class)->name('admin.master-ujian.dashboard');
        Route::get('/detail/{ujianId}', DetailUjian::class)->name('admin.master-ujian.detail');
        Route::get('/preview/{ujianId}', PreviewUjian::class)->name('admin.psb.ujian.preview');
        Route::get('/essay/{ujianId}', UjianEssay::class)->name('admin.master-ujian.essay');
    });
    
    // PSB Exam Results Routes
    Route::prefix('psb/ujian')->name('admin.psb.ujian.')->group(function () {
        Route::get('/hasil', HasilUjianSantri::class)->name('hasil');
        Route::get('/detail/{id}', DetailUjianSantri::class)->name('detail');
    });
    
});

