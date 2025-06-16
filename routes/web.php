<?php
use App\Livewire\Admin\AdminPiket\Dashboard;
use App\Livewire\Santri\Absensi;
use App\Livewire\Auth;
use App\Livewire\Santri\Scanner;
use App\Livewire\StudentExam;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});
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

// Rute untuk admin, tetap publik tanpa middleware
Route::get('/admin/piket/dashboard', Dashboard::class)->name('admin.piket.dashboard');


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