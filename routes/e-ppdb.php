<?php

use App\Livewire\Admin\PSB\DetailSoalSantri;
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
use App\Http\Controllers\Auth\SantriAuthController;
use App\Livewire\Guest\CetakPenerimaanSurat;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log; // Tambahkan ini jika belum ada
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika belum ada
// Rute untuk tamu (tanpa autentikasi)
Route::middleware('guest:santri')->group(function () {
    Route::get('/registerppdb', RegisterSantri::class)->name('register-santri');
    Route::get('/loginppdbsantri', LoginPsb::class)->name('login-ppdb-santri');
    Route::post('/login-ppdb', [SantriAuthController::class, 'login'])->name('login-ppdb-santri.post');
    Route::get('/check-status', CheckStatus::class)->name('check-status');
    Route::get('/pendaftaran-santri', PsbPage::class)->name('psb-page');
    // Rute BARU untuk halaman cetak penerimaan surat
    // {registrationId} adalah parameter yang akan diteruskan ke metode mount() komponen Livewire
    // Route::get('/psb/cetak-penerimaan/{registrationId}', CetakPenerimaanSurat::class)->name('psb.cetak-penerimaan');
    
});

// Logout route for santri
Route::post('/logout-santri', function () {
    Auth::guard('santri')->logout();
    session()->forget(['santri_id', 'login_time']);
    return redirect()->route('login-ppdb-santri');
})->name('logout-ppdb-santri');

// Rute untuk admin PSB
Route::prefix('ppdb')->middleware(['auth', 'role:Pendaftaran Santri'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\PSB\Dashboard::class)->name('e-ppdb.dashboard');
    
    Route::prefix('master-psb')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\PSB\Dashboard::class)->name('admin.master-psb.dashboard');
        Route::get('/list-santri-baru', ShowRegistrations::class)->name('admin.master-psb.show-registrations');
        Route::get('/list-santri-baru/{santriId}', DetailRegistration::class)->name('admin.master-psb.detail-registration');
        Route::get('/list-santri-baru/{santriId}/edit', \App\Livewire\Admin\PSB\EditRegistration::class)->name('admin.master-psb.edit-registration');
        Route::get('/list-wawancara-santri', InterviewList::class)->name('admin.master-psb.interview-list');
        Route::get('/list-wawancara', InterviewList::class)->name('master-psb.list-wawancara');
        Route::get('/sertifikat', \App\Livewire\Admin\PSB\SertifikatPenerimaan::class)->name('admin.master-psb.sertifikat');
    });
    
    Route::prefix('master-periode')->middleware(['auth', 'role:Pendaftaran Santri'])->group(function () {
        Route::get('/dashboard-periode', PeriodeManager::class)->name('admin.master-periode.dashboard');
    });
    
    Route::prefix('master-ujian')->middleware(['auth', 'role:Pendaftaran Santri'])->group(function () {
        Route::get('/dashboard', DashboardUjian::class)->name('admin.master-ujian.dashboard');
        Route::get('/detail/{ujianId}', DetailUjian::class)->name('admin.master-ujian.detail');
        Route::get('/preview/{ujianId}', PreviewUjian::class)->name('admin.psb.ujian.preview');
        Route::get('/essay/{ujianId}', UjianEssay::class)->name('admin.master-ujian.essay');
        Route::get('/detail/{ujianId}/{santriId}', \App\Livewire\Admin\PSB\DetailSoalSantri::class)->name('master-ujian.detail-soal');
        Route::get('/hasil', \App\Livewire\Admin\PSB\HasilUjian::class)->name('master-ujian.hasil');
        Route::get('/detail-santri/{santriId}', \App\Livewire\Admin\PSB\DetailUjianSantri::class)->name('master-ujian.detail-santri');
    });
    
    // PSB Exam Results Routes
    Route::prefix('psb/ujian')->name('admin.psb.ujian.')->middleware(['auth', 'role:Pendaftaran Santri'])->group(function () {
        Route::get('/hasil', HasilUjianSantri::class)->name('hasil');
        Route::get('/detail/{id}', DetailUjianSantri::class)->name('detail');
        Route::get('/detail-soal/{ujianId}/{santriId}', DetailSoalSantri::class)
            ->middleware(['role:Pendaftaran Santri'])
            ->name('detail-soal');
    });

    // Daftar Ulang Routes
    Route::get('/list-daftar-ulang', \App\Livewire\Admin\PSB\ListDaftarUlang::class)->name('list-daftar-ulang');
    Route::get('/dashboard-daftar-ulang', \App\Livewire\Admin\PSB\DashboardDaftarUlang::class)
        ->middleware(['role:Pendaftaran Santri'])
        ->name('ppdb.dashboard-daftar-ulang');
    Route::get('/daftar-ulang', \App\Livewire\SantriPPDB\PendaftaranUlang::class)
        ->middleware(['role:Pendaftaran Santri'])
        ->name('ppdb.daftar-ulang');
});

// Rute untuk santri PPDB (dengan autentikasi)
Route::middleware(['auth:pendaftaran_santri'])->group(function () {
    // Exam Routes
    Route::get('/mulai-ujian/{ujianId}', \App\Livewire\SantriPPDB\MulaiUjian::class)->name('santri.mulai-ujian');
    Route::get('/ujian-santri/{ujianId}', \App\Livewire\SantriPPDB\UjianSantri::class)->name('santri.ujian');
    Route::get('/konfirmasi-ujian/{ujianId}', \App\Livewire\SantriPPDB\KonfirmasiUjian::class)->name('santri.konfirmasi-ujian');
    Route::get('/selesai-ujian/{ujianId}', \App\Livewire\SantriPPDB\SelesaiUjian::class)->name('santri.selesai-ujian');
    Route::get('/dashboard-ujian', \App\Livewire\SantriPPDB\DashboardUjianSantri::class)->name('santri.dashboard-ujian');
    Route::get('/riwayat-ujian', \App\Livewire\SantriPPDB\RiwayatUjian::class)->name('santri.riwayat-ujian');
    
    // Daftar Ulang Route
    Route::get('/daftar-ulang', \App\Livewire\SantriPPDB\PendaftaranUlang::class)->name('santri.daftar-ulang');
});

// Rute untuk mengunduh PDF secara langsung
Route::get('/psb/download-penerimaan-pdf/{santriId}', [App\Http\Controllers\PSB\SuratPenerimaanController::class, 'download'])
    ->name('psb.download-penerimaan-pdf');

// Route untuk pengaturan daftar ulang
Route::middleware(['auth', 'role:Pendaftaran Santri'])->group(function () {
    Route::get('/psb/daftar-ulang-settings', App\Livewire\Admin\PSB\DaftarUlangSettings::class)->name('ppdb.daftar-ulang-settings');
});