

<?php
use App\Livewire\Admin\PSB\PsbPage;
use App\Livewire\Auth\LoginPsb;
use App\Livewire\Auth\RegisterSantri;
use Illuminate\Support\Facades\Route;

Route::get('/registerppdb', RegisterSantri::class)->name('register-santri');
Route::get('/loginppdbsantri', LoginPsb::class)->name('login-ppdb-santri');
Route::get('/check-status', \App\Livewire\Guest\CheckStatus::class)->name('check-status');
Route::get('/pendaftaran-santri', PsbPage::class)->name('psb-page');



