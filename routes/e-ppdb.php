

<?php

use App\Livewire\Admin\PSB\PsbPage;
use App\Livewire\Auth\RegisterSantri;
use Illuminate\Support\Facades\Route;

Route::get('/register-santri', RegisterSantri::class)->name('register-santri');
Route::get('/check-status', \App\Livewire\Guest\CheckStatus::class)->name('check-status');
Route::get('/ppdb2025', PsbPage::class)->name('psb-page');

