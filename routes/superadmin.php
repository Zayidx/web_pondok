<?php

use App\Livewire\PSB\DetailRegistration;
use App\Livewire\PSB\ShowRegistrations;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin;
use App\Livewire\Admin\ListSantri\DetailSantri;

Route::prefix('admin')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', Admin\Dashboard::class)->name('admin.dashboard');

    // Data Master Pondok
    Route::prefix('master-pondok')->group(function () {
        Route::get('/jenjang', Admin\Jenjang::class)->name('admin.master-pondok.jenjang');
        Route::get('/kelas', Admin\Kelas::class)->name('admin.master-pondok.kelas');
        Route::get('/wali-kelas', Admin\WaliKelas::class)->name('admin.master-pondok.wali-kelas');
        Route::get('/kamar', Admin\Kamar::class)->name('admin.master-pondok.kamar');
        Route::get('/wali-kamar', Admin\WaliKamar::class)->name('admin.master-pondok.wali-kamar');
        Route::get('/angkatan', Admin\Angkatan::class)->name('admin.master-pondok.angkatan');
        Route::get('/semester', Admin\Semester::class)->name('admin.master-pondok.semester');
    });

    // Data Master Santri
    Route::prefix('master-santri')->group(function () {
        Route::get('/list-santri', Admin\ListSantri::class)->name('admin.master-santri.santri');
        Route::get('/list-santri/detail-santri/{id}', DetailSantri::class)->name('admin.master-santri.detail-santri');

        Route::get('/list-wali-santri', Admin\ListWaliSantri::class)->name('admin.master-santri.wali-santri');

        // Services
        Route::get('/list-santri/export/', [Admin\ListSantri::class, 'export']);
    });

    // Data Master Admin
    Route::prefix('master-admin')->group(function () {
        Route::get('/list-admin', Admin\ListAdmin::class)->name('admin.master-admin.list-admin');
        Route::get('/list-role', Admin\ListRole::class)->name('admin.master-admin.list-role');

        // Service
    });

    Route::prefix('master-aktifitas')->group(function () {
        Route::get('/pengumuman', Admin\Pengumuman::class)->name('admin.master-aktifitas.pengumuman');
        Route::get('/kegiatan', Admin\Kegiatan::class)->name('admin.master-aktifitas.kegiatan');
    });
    
    //PSB 
    Route::prefix('master-psb')->group(function () {
        Route::get('/show-registrations', \App\Livewire\Admin\PSB\ShowRegistrations::class)->name('admin.show-registrations');
        Route::get('/show-registrations/{santriId}', \App\Livewire\Admin\PSB\DetailRegistration::class)->name('admin.show-registration.detail');
        Route::get('/wawancara-santri', \App\Livewire\Admin\PSB\InterviewList::class)->name('admin.interview-list');
    });
   
   
});
