<?php

use App\Livewire\Admin;
use App\Livewire\Admin\ListSantri\DetailSantri;
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

Route::prefix('admin')->middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('/dashboard', Admin\Dashboard::class)->name('admin.dashboard');

    Route::prefix('master-pondok')->group(function () {
        Route::get('/jenjang', Admin\Jenjang::class)->name('admin.master-pondok.jenjang');
        Route::get('/kelas', Admin\Kelas::class)->name('admin.master-pondok.kelas');
        Route::get('/wali-kelas', Admin\WaliKelas::class)->name('admin.master-pondok.wali-kelas');
        Route::get('/kamar', Admin\Kamar::class)->name('admin.master-pondok.kamar');
        Route::get('/wali-kamar', Admin\WaliKamar::class)->name('admin.master-pondok.wali-kamar');
        Route::get('/angkatan', Admin\Angkatan::class)->name('admin.master-pondok.angkatan');
        Route::get('/semester', Admin\Semester::class)->name('admin.master-pondok.semester');
    });

    Route::prefix('master-santri')->group(function () {
        Route::get('/list-santri', Admin\ListSantri::class)->name('admin.master-santri.santri');
        Route::get('/list-santri/detail-santri/{id}', DetailSantri::class)->name('admin.master-santri.detail-santri');
        Route::get('/list-wali-santri', Admin\ListWaliSantri::class)->name('admin.master-santri.wali-santri');
        Route::get('/list-santri/export', [Admin\ListSantri::class, 'export'])->name('admin.master-santri.export');
    });

    Route::prefix('master-admin')->group(function () {
        Route::get('/list-admin', Admin\ListAdmin::class)->name('admin.master-admin.list-admin');
        Route::get('/list-role', Admin\ListRole::class)->name('admin.master-admin.list-role');
    });

    Route::prefix('master-aktifitas')->group(function () {
        Route::get('/pengumuman', Admin\Pengumuman::class)->name('admin.master-aktifitas.pengumuman');
        Route::get('/kegiatan', Admin\Kegiatan::class)->name('admin.master-aktifitas.kegiatan');
    });

    Route::prefix('master-psb')->group(function () {
        Route::get('/dashboard', \App\Livewire\SantriPPDB\SantriDashboard::class)->name('santrippdb.dashboard');
        Route::get('/registrations', ShowRegistrations::class)->name('admin.master-psb.show-registrations');
        Route::get('/registrations/{santriId}', DetailRegistration::class)->name('admin.master-psb.detail-registration');
        Route::get('/registrations/{santriId}/edit', \App\Livewire\Admin\PSB\EditRegistration::class)->name('admin.master-psb.edit-registration');
        Route::get('/wawancara-santri', InterviewList::class)->name('admin.master-psb.interview-list');
    });

    Route::prefix('master-periode')->group(function () {
        Route::get('/dashboard', PeriodeManager::class)->name('admin.master-periode.dashboard');
    });

    Route::prefix('master-ujian')->group(function () {
        Route::get('/dashboard', DashboardUjian::class)->name('admin.master-ujian.dashboard');
        Route::get('/detail/{ujianId}', DetailUjian::class)->name('admin.master-ujian.detail');
        Route::get('/preview/{ujianId}', PreviewUjian::class)->name('admin.psb.ujian.preview');
        Route::get('/essay/{ujianId}', UjianEssay::class)->name('admin.master-ujian.essay');
    });

    Route::prefix('psb/ujian')->name('admin.psb.ujian.')->group(function () {
        Route::get('/hasil', HasilUjianSantri::class)->name('hasil');
        Route::get('/detail/{id}', DetailUjianSantri::class)->name('detail');
    });
});