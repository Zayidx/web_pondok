<?php

use App\Livewire\Admin\ESantri\GuruUmum\Absensi;
use App\Livewire\Santri\Scanner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\ESantri\GuruUmum;

Route::prefix('santri')->middleware(['web', 'auth:santri'])->group(function () {
    Route::get('/scanner', Scanner::class)->name('santri.scanner');
    

});
Route::get('/absensi/{token}', Absensi::class)->name('santri.absensi');
Route::prefix('e-santri')->middleware('auth')->group(function () {
    Route::prefix('guru')->group(function () {
        Route::get('/dashboard', GuruUmum\DashboardGuruUmum::class)->name('e-santri-guru-umum.dashboard');
        
        Route::get('/jadwal-pelajaran', GuruUmum\JadwalPelajaran::class)->name('e-santri-guru-umum.jadwal-pelajaran');
        Route::get('/kategori-pelajaran', GuruUmum\KategoriPelajaran::class)->name('e-santri-guru-umum.kategori-pelajaran');
        Route::get('/jadwal-piket', GuruUmum\JadwalPiket::class)->name('e-santri-guru-umum.jadwal-piket');
        Route::get('/pengumuman', GuruUmum\Pengumuman::class)->name('e-santri-guru-umum.pengumuman');
        Route::get('/absensi',  GuruUmum\Absensi::class)->name('e-santri-guru-umum.absensi');
    });
});
