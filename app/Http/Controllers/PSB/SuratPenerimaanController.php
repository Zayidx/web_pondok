<?php

namespace App\Http\Controllers\PSB;

use App\Http\Controllers\Controller;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\SuratPenerimaanSetting;
use Illuminate\Http\Request;
use Carbon\Carbon; // <-- Tambahkan ini
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;


class SuratPenerimaanController extends Controller
{
    /**
     * Fungsi private untuk mengubah gambar menjadi string Base64.
     * Base64 diperlukan agar gambar bisa langsung disematkan (embed) ke dalam file PDF
     * tanpa perlu memuatnya dari URL eksternal.
     *
     * @param string|null $path Path file yang tersimpan di File.
     * @return string|null String Base64 atau null jika gagal.
     */
    /**
     * Mengubah path gambar menjadi string Base64.
     */
   

    /**
     * Menampilkan halaman preview sertifikat (bukan PDF, tapi halaman HTML biasa).
     * @param int $id ID pendaftaran santri.
     */
    public function previewPage($id)
    {
        // Mencari data santri berdasarkan ID, akan gagal jika tidak ditemukan.
        $data = PendaftaranSantri::findOrFail($id);
        // Mengambil baris pertama dari pengaturan surat penerimaan.
        $settings = SuratPenerimaanSetting::first();

        // Jika pengaturan tidak ada di database, kembalikan ke halaman sebelumnya dengan pesan error.
        if (!$settings) {
            return redirect()->route('psb.check-status')->with('error', 'Pengaturan surat penerimaan belum dikonfigurasi.');
        }

      // PERBAIKAN: Menggunakan File::get() untuk konsistensi dan praktik terbaik.
      $settings->logo_base64 = base64_encode(File::get($settings->logo));
     


        // Menampilkan view 'psb.preview-sertifikat' dengan data santri dan pengaturan.
        return view('psb.preview-sertifikat', compact('data', 'settings'));
    }

    /**
     * Menampilkan preview surat penerimaan dalam bentuk PDF di browser (stream).
     * @param int $id ID pendaftaran santri.
     */
    public function preview($id)
    {
        // Mencari data santri berdasarkan ID.
        $data = PendaftaranSantri::findOrFail($id);
        // Mengambil pengaturan surat.
        $settings = SuratPenerimaanSetting::first();

        // Memeriksa apakah pengaturan ada.
        if (!$settings) {
            return redirect()->route('psb.check-status')->with('error', 'Pengaturan surat penerimaan belum dikonfigurasi.');
        }

       // PERBAIKAN: Menggunakan File::get() untuk konsistensi dan praktik terbaik.
       $settings->logo_base64 = base64_encode(File::get($settings->logo));
       $settings->ttd_direktur_base64 = base64_encode(File::get($settings->ttd_direktur));
       $settings->ttd_admin_base64 = base64_encode(File::get($settings->ttd_admin));


        // Memuat view blade yang akan dijadikan PDF.
       $pdf = PDF::loadView('psb.surat-penerimaan',
        [
            'logo' => $settings->logo_base64,
            'ttd_direktur' => $settings->ttd_direktur_base64,
            'ttd_admin' => $settings->ttd_admin_base64,
        ]
        ,compact('data', 'settings'));
        // Mengatur ukuran kertas menjadi A4 dengan orientasi potret.
        $pdf->setPaper('a4', 'portrait');
        // Menampilkan PDF di browser tanpa mengunduhnya.
        return $pdf->stream('surat-penerimaan.pdf');
    }

    /**
     * Mengunduh surat penerimaan dalam format PDF.
     * @param int $id ID pendaftaran santri.
     */
    public function download($id)
    {
        $data = PendaftaranSantri::findOrFail($id);;
        $settings = SuratPenerimaanSetting::first();
        if (!$settings) {
            return redirect()->route('psb.check-status')->with('error', 'Pengaturan surat penerimaan belum dikonfigurasi.');
        }
        
         $settings->logo_base64 = base64_encode(File::get($settings->logo));
         
         $settings->ttd_direktur_base64 = base64_encode(File::get($settings->ttd_direktur));
         $settings->ttd_admin_base64 = base64_encode(File::get($settings->ttd_admin));
        $pdf = PDF::loadView('psb.surat-penerimaan',
        [
            'logo' => $settings->logo_base64,
            'ttd_direktur' => $settings->ttd_direktur_base64,
            'ttd_admin' => $settings->ttd_admin_base64,
        ]
        ,compact('data', 'settings'));
        $pdf->setPaper('a4', 'portrait');
        // Memulai proses unduh file PDF dengan nama file yang dinamis.
        return $pdf->download('surat-penerimaan-' . $data->nama_lengkap . '.pdf');
    }

    /**
     * Mencetak surat penerimaan (biasanya membuka dialog print di browser).
     * @param int $id ID pendaftaran santri.
     */
    public function print($id)
    {
        // Logika di sini juga identik dengan fungsi preview.
        $data = PendaftaranSantri::findOrFail($id);
        $settings = SuratPenerimaanSetting::first();

        if (!$settings) {
            return redirect()->route('psb.check-status')->with('error', 'Pengaturan surat penerimaan belum dikonfigurasi.');
        }

     // PERBAIKAN: Menggunakan File::get() untuk konsistensi dan praktik terbaik.
     $settings->logo_base64 = base64_encode(File::get($settings->logo));
     $settings->ttd_direktur_base64 = base64_encode(File::get($settings->ttd_direktur));
     $settings->ttd_admin_base64 = base64_encode(File::get($settings->ttd_admin));


       $pdf = PDF::loadView('psb.surat-penerimaan',
        [
            'logo' => $settings->logo_base64,
            'ttd_direktur' => $settings->ttd_direktur_base64,
            'ttd_admin' => $settings->ttd_admin_base64,
        ]
        ,compact('data', 'settings'));
        $pdf->setPaper('a4', 'portrait');
        // Stream PDF ke browser, yang biasanya akan memicu dialog cetak jika dibuka di tab baru.
        return $pdf->stream('surat-penerimaan.pdf');
    }
}
