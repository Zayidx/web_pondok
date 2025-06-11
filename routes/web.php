<?php
// routes/web.php

use App\Http\Controllers\CertificateController; // Ini bisa dihapus jika controllernya dihapus
use App\Livewire\Auth;
use App\Livewire\StudentExam;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

// >>>>>> INI ADALAH DEFINISI RUTE YANG PERLU DIHAPUS ATAU DIKOMENTARI <<<<<<
// Karena sekarang unduhan ditangani langsung oleh komponen Livewire PSB/CetakPenerimaanSurat
// Route::post('/download-certificate-pdf', [CertificateController::class, 'download'])->name('download-certificate-pdf');

// >>>>>> KODE DI BAWAH INI HARUS DIHAPUS ATAU DIKOMENTARI (Sudah dikomentari sebelumnya) <<<<<<
/*
Route::post('/download-certificate-pdf', function (Request $request) {
    $htmlContent = $request->input('html_content');
    $fileName = $request->input('file_name', 'sertifikat.pdf'); // Default filename

    // Perbaiki URL CDN di dalam HTML jika ada
    $htmlContent = str_replace(
        ['[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)', '[https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap](https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap)'],
        ['https://cdn.tailwindcss.com', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap'],
        $htmlContent
    );

    $pdf = Pdf::loadHtml($htmlContent);

    // Mengembalikan PDF sebagai unduhan
    return $pdf->download($fileName);
})->name('download-certificate-pdf');
*/
// >>>>>> BATAS KODE YANG HARUS DIHAPUS/DIKOMENTARI <<<<<<

// Route redirect
require __DIR__ . '/superadmin.php';
require __DIR__ . '/e-spp.php';
require __DIR__ . '/e-ppdb.php';
require __DIR__ . '/e-santri.php';
require __DIR__ . '/e-cashless/petugas-e-cashless.php';
require __DIR__ . '/e-cashless/petugas-laundry.php';
require __DIR__ . '/e-cashless/petugas-warung.php';