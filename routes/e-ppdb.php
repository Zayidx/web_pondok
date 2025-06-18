<?php

use App\Http\Controllers\Auth\SantriAuthController;
use App\Http\Controllers\PSB\SuratPenerimaanController;
use App\Livewire\Admin\PSB;
use App\Livewire\Auth\LoginPsb;
use App\Livewire\Auth\RegisterSantri;
use App\Livewire\Guest\CheckStatus;
use App\Livewire\Guest\CetakPenerimaanSurat;
use App\Livewire\Guest\PsbPage;
use App\Livewire\SantriPPDB;
use App\Models\PSB\PendaftaranSantri;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:santri')->group(function () {
    Route::get('/registerppdb', RegisterSantri::class)->name('register-santri');
    Route::get('/loginppdbsantri', LoginPsb::class)->name('login-ppdb-santri');
    Route::post('/login-ppdb', [SantriAuthController::class, 'login'])->name('login-ppdb-santri.post');
    Route::get('/check-status', CheckStatus::class)->name('check-status');
    Route::get('/psb/cetak-penerimaan/{registrationId}', CetakPenerimaanSurat::class)->name('psb.cetak-penerimaan');
    Route::get('/pendaftaran-santri', \App\Livewire\Admin\PSB\PsbPage::class)->name('psb-page');
});

Route::post('/logout-santri', function () {
    Auth::guard('santri')->logout();
    session()->forget(['santri_id', 'login_time']);
    return redirect()->route('login-ppdb-santri');
})->name('logout-ppdb-santri');

Route::middleware(['auth:pendaftaran_santri'])->group(function () {
    Route::get('/dashboard-ujian', SantriPPDB\DashboardUjianSantri::class)->name('santri.dashboard-ujian');
    Route::get('/mulai-ujian/{ujianId}', SantriPPDB\MulaiUjian::class)->name('santri.mulai-ujian');
    Route::get('/ujian-santri/{ujianId}', SantriPPDB\UjianSantri::class)->name('santri.ujian');
    Route::get('/konfirmasi-ujian/{ujianId}', SantriPPDB\KonfirmasiUjian::class)->name('santri.konfirmasi-ujian');
    Route::get('/selesai-ujian/{ujianId}', SantriPPDB\SelesaiUjian::class)->name('santri.selesai-ujian');
    Route::get('/riwayat-ujian', SantriPPDB\RiwayatUjian::class)->name('santri.riwayat-ujian');
    Route::get('/daftar-ulang', SantriPPDB\PendaftaranUlang::class)->name('santri.daftar-ulang');
});

Route::prefix('ppdb')->middleware(['auth', 'role:Pendaftaran Santri'])->group(function () {
    Route::get('/dashboard', PSB\Dashboard::class)->name('e-ppdb.dashboard');

    Route::prefix('master-psb')->name('admin.master-psb.')->group(function () {
        Route::get('/dashboard', PSB\Dashboard::class)->name('dashboard');
        Route::get('/list-santri-baru', PSB\ShowRegistrations::class)->name('show-registrations');
        Route::get('/list-santri-baru/{santriId}', PSB\DetailRegistration::class)->name('detail-registration');
        Route::get('/list-santri-baru/{santriId}/edit', PSB\EditRegistration::class)->name('edit-registration');
        Route::get('/list-wawancara-santri', PSB\InterviewList::class)->name('interview-list');
        Route::get('/sertifikat', PSB\SertifikatPenerimaan::class)->name('sertifikat');
    });

    Route::prefix('master-periode')->name('admin.master-periode.')->group(function () {
        Route::get('/dashboard-periode', PSB\PeriodeManager::class)->name('dashboard');
    });

    Route::prefix('master-ujian')->name('admin.master-ujian.')->group(function () {
        Route::get('/dashboard', PSB\DashboardUjian::class)->name('dashboard');
        Route::get('/detail/{ujianId}', PSB\DetailUjian::class)->name('detail');
        Route::get('/preview/{ujianId}', PSB\PreviewUjian::class)->name('preview');
        Route::get('/essay/{ujianId}', PSB\UjianEssay::class)->name('essay');
        Route::get('/hasil', PSB\HasilUjian::class)->name('hasil');
        Route::get('/detail/{ujianId}/{santriId}', PSB\DetailSoalSantri::class)->name('detail-soal');
        Route::get('/detail-santri/{santriId}', PSB\DetailUjianSantri::class)->name('detail-santri');
    });

    Route::prefix('psb/ujian')->name('admin.psb.ujian.')->group(function () {
        Route::get('/hasil', PSB\HasilUjianSantri::class)->name('hasil');
        Route::get('/detail/{id}', PSB\DetailUjianSantri::class)->name('detail');
        Route::get('/detail-soal/{ujianId}/{santriId}', PSB\DetailSoalSantri::class)->name('detail-soal');
    });

    Route::get('/list-daftar-ulang', PSB\ListDaftarUlang::class)->name('list-daftar-ulang');
    Route::get('/dashboard-daftar-ulang', PSB\DashboardDaftarUlang::class)->name('ppdb.dashboard-daftar-ulang');
    Route::get('/daftar-ulang', SantriPPDB\PendaftaranUlang::class)->name('ppdb.daftar-ulang');
    Route::get('/psb/daftar-ulang-settings', PSB\DaftarUlangSettings::class)->name('ppdb.daftar-ulang-settings');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/psb/sertifikat', \App\Livewire\Admin\PSB\SertifikatPenerimaan::class)->name('admin.psb.sertifikat');
    
    Route::get('/admin/psb/detail-pendaftaran/{id}', function($id) {
        $registration = PendaftaranSantri::findOrFail($id);
        return view('psb.detail-pendaftaran', compact('registration'));
    })->name('admin.psb.detail-pendaftaran');
    
    Route::get('/admin/psb/lihat-bukti/{id}', function($id) {
        $registration = PendaftaranSantri::findOrFail($id);
        if (!$registration->bukti_pembayaran) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }
        return response()->file(storage_path('app/public/' . str_replace('public/', '', $registration->bukti_pembayaran)));
    })->name('admin.psb.lihat-bukti');
});

Route::get('/preview-surat-penerimaan', [SuratPenerimaanController::class, 'preview'])
    ->name('preview.surat-penerimaan');

Route::get('/ppdb/download-penerimaan-pdf/{santriId}', [SuratPenerimaanController::class, 'download'])
    ->name('psb.download-penerimaan-pdf');

Route::prefix('psb/surat-penerimaan')->name('psb.surat-penerimaan.')->group(function () {
    Route::get('/preview-page', [SuratPenerimaanController::class, 'previewPage'])->name('preview-page');
    Route::get('/preview', [SuratPenerimaanController::class, 'preview'])->name('preview');
    Route::get('/download', [SuratPenerimaanController::class, 'download'])->name('download');
    Route::get('/print', [SuratPenerimaanController::class, 'print'])->name('print');
});
