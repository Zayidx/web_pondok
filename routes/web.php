<?php
use App\Livewire\Auth;
use App\Livewire\StudentExam;
use App\Livewire\TestButton;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/auth/login');
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

Route::get('/ppdb/download-penerimaan-pdf/{santriId}', function ($santriId) {
    $santri = \App\Models\PSB\PendaftaranSantri::findOrFail($santriId);
    
    if ($santri->status_santri !== 'diterima') {
        abort(403, 'Surat penerimaan hanya dapat diunduh untuk santri yang diterima.');
    }

    $template = \App\Models\PSB\SertifikatTemplate::first();
    if (!$template) {
        abort(500, 'Template sertifikat belum dikonfigurasi.');
    }

    $periode = \App\Models\PSB\Periode::where('tipe_periode', 'pendaftaran_baru')
        ->where('status_periode', 'active')
        ->first();

    $santriName = $santri->nama_lengkap;
    $acceptanceDate = $santri->updated_at ? $santri->updated_at->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y');
    $issueDate = \Carbon\Carbon::now()->translatedFormat('d F Y');
    $certificateNumber = 'CERT/' . date('Y') . '/' . str_pad($santri->id, 6, '0', STR_PAD_LEFT);

    $logoPath = public_path('assets/compiled/jpg/1.jpg');
    $logoBase64 = '';

    if (\Illuminate\Support\Facades\File::exists($logoPath)) {
        $logoBase64 = 'data:image/' . \Illuminate\Support\Facades\File::extension($logoPath) . ';base64,' . base64_encode(\Illuminate\Support\Facades\File::get($logoPath));
    }

    $data = [
        'santri' => $santri,
        'template' => $template,
        'periode' => $periode,
        'tanggal_cetak' => \Carbon\Carbon::now()->translatedFormat('d F Y'),
        'acceptanceDate' => $acceptanceDate,
        'issueDate' => $issueDate,
        'certificateNumber' => $certificateNumber,
        'logoBase64' => $logoBase64,
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('psb.surat-penerimaan-pdf', $data);
    $fileName = 'Surat_Penerimaan_' . str_replace(' ', '_', $santriName) . '.pdf';
    
    return $pdf->download($fileName);
})->name('psb.download-penerimaan-pdf');