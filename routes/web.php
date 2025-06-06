<?php

use App\Livewire\Auth;
use App\Livewire\Auth\RegisterSantri;
use App\Livewire\PSB\CheckStatus;
use App\Livewire\PSB\DetailRegistration;
use App\Livewire\PSB\ShowRegistrations;
use App\Livewire\StudentExam;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SantriAuthController;

Route::get('/', function () {
    return redirect('/auth/login');
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

// Route redirect
require __DIR__ . '/superadmin.php';
require __DIR__ . '/e-spp.php';
require __DIR__ . '/e-ppdb.php';
require __DIR__ . '/e-santri.php';
require __DIR__ . '/e-cashless/petugas-e-cashless.php';
require __DIR__ . '/e-cashless/petugas-laundry.php';
require __DIR__ . '/e-cashless/petugas-warung.php';

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/master-psb/list-wawancara', App\Livewire\Admin\PSB\InterviewList::class)->name('master-psb.list-wawancara');
});

// Santri Auth Routes
Route::middleware('guest:santri')->group(function () {
    Route::get('/login-ppdb', [SantriAuthController::class, 'showLoginForm'])->name('login-ppdb-santri');
    Route::post('/login-ppdb', [SantriAuthController::class, 'login'])->name('login-ppdb-santri.post');
});

Route::post('/logout-santri', [SantriAuthController::class, 'logout'])->name('logout-santri');

Route::prefix('e-ppdb')->name('e-ppdb.')->group(function () {
    // Public routes
    Route::get('/check-status', CheckStatus::class)->name('check-status');
    
    // Ujian routes (require santri auth)
    Route::middleware(['auth:santri'])->group(function () {
        Route::get('/ujian/dashboard', \App\Livewire\SantriPPDB\DashboardUjianSantri::class)->name('ujian.dashboard');
        Route::get('/ujian/konfirmasi/{ujianId}', \App\Livewire\SantriPPDB\KonfirmasiUjian::class)->name('ujian.konfirmasi');
        Route::get('/ujian/hasil/{ujianId}', \App\Livewire\SantriPPDB\HasilUjian::class)->name('ujian.hasil');
    });
});

// Santri PPDB Routes
Route::middleware(['auth:santri'])->prefix('santri')->name('santri.')->group(function () {
    // Exam Routes
    Route::get('/mulai-ujian/{ujianId}', \App\Livewire\SantriPPDB\MulaiUjian::class)->name('mulai-ujian');
    Route::get('/selesai-ujian/{ujianId}', \App\Livewire\SantriPPDB\SelesaiUjian::class)->name('selesai-ujian');
    Route::get('/dashboard-ujian', \App\Livewire\SantriPPDB\DashboardUjianSantri::class)->name('dashboard-ujian');
});