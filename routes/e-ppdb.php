<?php

use App\Livewire\Admin\PSB\PsbPage;
use App\Livewire\Auth\LoginPsb;
use App\Livewire\Auth\RegisterSantri;
use App\Livewire\Guest\CheckStatus;
use App\Livewire\SantriDashboard;
use App\Livewire\UjianSantri;
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