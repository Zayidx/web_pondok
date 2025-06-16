<?php
use App\Livewire\Auth;
use App\Livewire\StudentExam;
use App\Livewire\TestButton;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PSB\SuratPenerimaanController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-livewire-button', TestButton::class);
Route::prefix('auth')->group(function () {
    Route::get('/login', Auth\Login::class)->name('login');
    Route::get('/login-santri', Auth\LoginSantri::class)->name('login-santri');
    Route::get('/register', Auth\Register::class)->name('register');
    Route::get('/logout', Auth\Logout::class)->name('auth.logout');
});

Route::get('/questions', StudentExam::class)->name('questions');

Route::fallback(function () {
    return view('404');
});

Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return 'Cache berhasil dibersihkan!';
});

Route::get('/generate', function () {
    Artisan::call('storage:link');
    echo 'ok';
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/psb/sertifikat', App\Livewire\Admin\PSB\SertifikatPenerimaan::class)->name('admin.psb.sertifikat');
    
    // Route untuk detail pendaftaran
    Route::get('/admin/psb/detail-pendaftaran/{id}', function($id) {
        $registration = \App\Models\PSB\PendaftaranSantri::findOrFail($id);
        return view('psb.detail-pendaftaran', compact('registration'));
    })->name('admin.psb.detail-pendaftaran');
    
    // Route untuk lihat bukti pembayaran
    Route::get('/admin/psb/lihat-bukti/{id}', function($id) {
        $registration = \App\Models\PSB\PendaftaranSantri::findOrFail($id);
        if (!$registration->bukti_pembayaran) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }
        return response()->file(storage_path('app/public/' . str_replace('public/', '', $registration->bukti_pembayaran)));
    })->name('admin.psb.lihat-bukti');
});

// Route untuk preview dan download surat penerimaan
Route::get('/preview-surat-penerimaan', [App\Http\Controllers\PSB\SuratPenerimaanController::class, 'preview'])
    ->name('preview.surat-penerimaan');

Route::get('/ppdb/download-penerimaan-pdf/{santriId}', [App\Http\Controllers\PSB\SuratPenerimaanController::class, 'download'])
    ->name('psb.download-penerimaan-pdf');

// Routes untuk Surat Penerimaan
Route::prefix('psb/surat-penerimaan')->name('psb.surat-penerimaan.')->group(function () {
    Route::get('/preview-page', [SuratPenerimaanController::class, 'previewPage'])->name('preview-page');
    Route::get('/preview', [SuratPenerimaanController::class, 'preview'])->name('preview');
    Route::get('/download', [SuratPenerimaanController::class, 'download'])->name('download');
    Route::get('/print', [SuratPenerimaanController::class, 'print'])->name('print');
});

// Route redirect
require __DIR__ . '/superadmin.php';
require __DIR__ . '/e-spp.php';
require __DIR__ . '/e-ppdb.php';
require __DIR__ . '/e-santri.php';
require __DIR__ . '/e-cashless/petugas-e-cashless.php';
require __DIR__ . '/e-cashless/petugas-laundry.php';
require __DIR__ . '/e-cashless/petugas-warung.php';

Route::post('/ppdb/logout', function () {
    \Illuminate\Support\Facades\Auth::guard('pendaftaran_santri')->logout();
    session()->forget(['santri_id', 'login_time']);
    return redirect()->route('login-ppdb-santri');
})->name('logout-ppdb-santri');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/psb/daftar-ulang-settings', App\Livewire\Admin\PSB\DaftarUlangSettings::class)->name('psb.daftar-ulang-settings');
});