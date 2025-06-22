<?php

use App\Http\Controllers\Auth\SantriAuthController;
use App\Http\Controllers\PSB\SuratPenerimaanController;

use App\Livewire\Admin\PSB\DaftarUlangSettings;
use App\Livewire\Admin\PSB\Dashboard;
use App\Livewire\Admin\PSB\DashboardDaftarUlang;
use App\Livewire\Admin\PSB\DashboardUjian;
use App\Livewire\Admin\PSB\DetailRegistration;
use App\Livewire\Admin\PSB\DetailSoalSantri;
use App\Livewire\Admin\PSB\DetailUjian;
use App\Livewire\Admin\PSB\DetailUjianSantri;
use App\Livewire\Admin\PSB\EditRegistration;
use App\Livewire\Admin\PSB\HasilUjian;
use App\Livewire\Admin\PSB\HasilUjianSantri;
use App\Livewire\Admin\PSB\InterviewList;
use App\Livewire\Admin\PSB\ListDaftarUlang;
use App\Livewire\Admin\PSB\PeriodeManager;
use App\Livewire\Admin\PSB\PreviewUjian;
use App\Livewire\Admin\PSB\SertifikatPenerimaan;
use App\Livewire\Admin\PSB\ShowRegistrations;
use App\Livewire\Admin\PSB\UjianEssay;
use App\Livewire\Auth\LoginPsb;
use App\Livewire\Auth\RegisterSantri;
use App\Livewire\Guest\CheckStatus;
use App\Livewire\Guest\CetakPenerimaanSurat;
use App\Livewire\PSB\DashboardUjianSantri;
use App\Livewire\PSB\KonfirmasiUjian;
use App\Livewire\PSB\MulaiUjian;
use App\Livewire\PSB\PendaftaranUlang;
use App\Livewire\PSB\RiwayatUjian;
use App\Livewire\PSB\SelesaiUjian;
use App\Livewire\PSB\UjianSantri;
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
    Route::get('/dashboard-ujian', DashboardUjianSantri::class)->name('santri.dashboard-ujian');
    Route::get('/mulai-ujian/{ujianId}', MulaiUjian::class)->name('santri.mulai-ujian');
    Route::get('/ujian-santri/{ujianId}', UjianSantri::class)->name('santri.ujian');
    Route::get('/konfirmasi-ujian/{ujianId}', KonfirmasiUjian::class)->name('santri.konfirmasi-ujian');
    Route::get('/selesai-ujian/{ujianId}', SelesaiUjian::class)->name('santri.selesai-ujian');
    Route::get('/riwayat-ujian', RiwayatUjian::class)->name('santri.riwayat-ujian');
    Route::get('/daftar-ulang', PendaftaranUlang::class)->name('santri.daftar-ulang');
});

Route::prefix('ppdb')->middleware(['auth', 'role:Pendaftaran Santri'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('e-ppdb.dashboard');

    Route::prefix('master-psb')->name('admin.master-psb.')->group(function () {
        Route::get('/list-santri-baru', ShowRegistrations::class)->name('show-registrations');
        Route::get('/list-santri-baru/{santriId}', DetailRegistration::class)->name('detail-registration');
        Route::get('/list-santri-baru/{santriId}/edit', EditRegistration::class)->name('edit-registration');
        Route::get('/list-wawancara-santri', InterviewList::class)->name('interview-list');
        Route::get('/sertifikat', SertifikatPenerimaan::class)->name('sertifikat');
    });

    Route::prefix('master-periode')->name('admin.master-periode.')->group(function () {
        Route::get('/dashboard-periode', PeriodeManager::class)->name('dashboard');
    });

    Route::prefix('master-ujian')->name('admin.master-ujian.')->group(function () {
        Route::get('/dashboard', DashboardUjian::class)->name('dashboard');
        Route::get('/detail/{ujianId}', DetailUjian::class)->name('detail');
        Route::get('/preview/{ujianId}', PreviewUjian::class)->name('preview');
        Route::get('/essay/{ujianId}', UjianEssay::class)->name('essay');
        Route::get('/hasil', HasilUjian::class)->name('hasil');
        Route::get('/detail/{ujianId}/{santriId}', DetailSoalSantri::class)->name('detail-soal');
        Route::get('/detail-santri/{santriId}', DetailUjianSantri::class)->name('detail-santri');
    });

    Route::prefix('psb/ujian')->name('admin.psb.ujian.')->group(function () {
        Route::get('/hasil', HasilUjianSantri::class)->name('hasil');
        Route::get('/detail/{id}', DetailUjianSantri::class)->name('detail');
        Route::get('/preview/{ujianId}', PreviewUjian::class)->name('preview'); 
        Route::get('/detail-soal/{ujianId}/{santriId}', DetailSoalSantri::class)->name('detail-soal');
    });

    Route::get('/list-daftar-ulang', ListDaftarUlang::class)->name('list-daftar-ulang');
    Route::get('/dashboard-daftar-ulang', DashboardDaftarUlang::class)->name('ppdb.dashboard-daftar-ulang');
    Route::get('/daftar-ulang', PendaftaranUlang::class)->name('ppdb.daftar-ulang');
    Route::get('/psb/daftar-ulang-settings', DaftarUlangSettings::class)->name('ppdb.daftar-ulang-settings');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/psb/sertifikat', SertifikatPenerimaan::class)->name('admin.psb.sertifikat');
    
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
