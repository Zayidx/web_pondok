<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan Anda sudah menginstal paket ini

class CertificateController extends Controller
{
    /**
     * Mengunduh sertifikat PDF dari konten HTML yang diberikan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        // Mendapatkan konten HTML dari input request
        $htmlContent = $request->input('html_content');
        // Mendapatkan nama file dari input request, atau menggunakan nama default
        $fileName = $request->input('file_name', 'sertifikat_penerimaan.pdf');

        // Jika konten HTML kosong, kembalikan dengan pesan error
        if (empty($htmlContent)) {
            // Anda bisa mengarahkan kembali atau menampilkan error yang lebih baik
            return back()->with('error', 'Konten sertifikat tidak ditemukan.');
        }

        // Memuat HTML ke Dompdf dan mengembalikan sebagai unduhan
        // Pastikan konfigurasi Dompdf sudah benar untuk asset CSS/gambar jika ada
        $pdf = Pdf::loadHtml($htmlContent);
        return $pdf->download($fileName);
    }
}
