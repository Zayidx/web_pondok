<?php
// app/Http/Controllers/CertificateController.php
// File ini tidak lagi diperlukan jika menggunakan solusi Livewire baru.
// Anda bisa menghapusnya atau mengomentari isinya.

/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function download(Request $request)
    {
        $htmlContent = $request->input('html_content');
        $fileName = $request->input('file_name', 'sertifikat.pdf');

        $pdf = Pdf::loadHtml($htmlContent);

        return $pdf->download($fileName);
    }
}
*/