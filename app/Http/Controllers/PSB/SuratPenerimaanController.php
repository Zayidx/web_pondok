<?php
// File: app/Http/Controllers/PSB/SuratPenerimaanController.php

namespace App\Http\Controllers\PSB;

// Mengimpor kelas-kelas yang diperlukan.
use App\Http\Controllers\Controller;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\SuratPenerimaanSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\File; // Impor Fassad File untuk bekerja dengan path fisik.

class SuratPenerimaanController extends Controller
{
    /**
     * Helper function untuk mengubah path file gambar menjadi format URI data Base64.
     * Menggunakan metode public_path() dan File facade sesuai permintaan.
     *
     * @param string|null $dbPath Path file dari database (misal: 'surat-penerimaan/logo.png').
     * @return string|null String Base64 atau null jika gagal.
     */
    private function getBase64Image($dbPath)
    {
        // 1. Jika path dari database kosong, langsung hentikan fungsi.
        if (!$dbPath) {
            return null;
        }

        // 2. Bersihkan path jika mengandung 'public/'.
        $cleanedPath = str_replace('public/', '', $dbPath);
        
        // 3. Buat path absolut ke file di dalam folder public/storage.
        // Ini adalah inti dari metode yang Anda minta.
        $absolutePath = public_path('storage/' . $cleanedPath);

        // 4. Periksa apakah file benar-benar ada di path absolut tersebut.
        if (!File::exists($absolutePath)) {
            Log::warning('File gambar untuk PDF tidak ditemukan di path fisik: ' . $absolutePath);
            return null;
        }

        try {
            // 5. Baca data biner (isi) dari file menggunakan path absolut.
            $fileData = File::get($absolutePath);

            // 6. Dapatkan tipe MIME dari file untuk data URI.
            $mime = File::mimeType($absolutePath);

            // 7. Gabungkan menjadi format Data URI yang siap dipakai di tag <img>.
            return "data:{$mime};base64," . base64_encode($fileData);
        } catch (\Exception $e) {
            // Jika terjadi error saat membaca file, catat di log.
            Log::error('Gagal membaca file dari public_path untuk konversi Base64: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Fungsi utama untuk menyiapkan data dan membuat PDF.
     * @param int $id ID pendaftaran santri.
     * @param string $action Aksi yang akan dilakukan ('download' atau 'stream').
     */
    private function generatePdf($id, $action = 'stream')
    {
        // Mengambil data santri beserta relasi 'periode' dan 'wali'.
        $santri = PendaftaranSantri::with(['periode', 'wali'])->findOrFail($id);
        // Mengambil data pengaturan surat.
        $settings = SuratPenerimaanSetting::first();

        // Jika pengaturan belum ada, tampilkan pesan error.
        if (!$settings) {
            abort(500, 'Pengaturan surat penerimaan belum dikonfigurasi oleh admin.');
        }

        // --- MENYIAPKAN SEMUA DATA DINAMIS UNTUK VIEW ---
        
        // 1. Panggil fungsi getBase64Image yang baru untuk logo dan stempel.
        $settings->logo_base64 = $this->getBase64Image($settings->logo);
        $settings->stempel_base64 = $this->getBase64Image($settings->stempel);
        
        // 2. Siapkan data-data lainnya.
        $periode_pendaftaran = $santri->periode->nama_periode ?? $settings->tahun_ajaran;
        $jenjang_diterima = $santri->nama_jenjang ?? 'Tidak Diketahui';
        $catatan_list = isset($settings->catatan_penting) ? explode("\n", $settings->catatan_penting) : [];
        $nomor_surat = 'PSB/' . ($settings->tahun_ajaran ?? date('Y')) . '/P/' . str_pad($santri->id, 4, '0', STR_PAD_LEFT);
        $tanggal_terbit = Carbon::now()->translatedFormat('d F Y');
        $tempat_terbit = 'Jakarta';

        // 3. Kumpulkan semua variabel ke dalam satu array untuk dikirim ke view.
        $data = [
            'santri' => $santri,
            'settings' => $settings,
            'periode_pendaftaran' => $periode_pendaftaran,
            'jenjang_diterima' => $jenjang_diterima,
            'catatan_list' => $catatan_list,
            'nomor_surat' => $nomor_surat,
            'tanggal_terbit' => $tanggal_terbit,
            'tempat_terbit' => $tempat_terbit,
        ];

        // Membuat instance PDF.
        $pdf = PDF::loadView('psb.surat-penerimaan-pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        // Menentukan aksi berdasarkan parameter.
        if ($action === 'download') {
            return $pdf->download('surat-penerimaan-' . $santri->nama_lengkap . '.pdf');
        }

        return $pdf->stream('surat-penerimaan-' . $santri->nama_lengkap . '.pdf');
    }

    // Metode publik di bawah ini tidak perlu diubah karena mereka hanya memanggil generatePdf.
    
    public function preview($id)
    {
        return $this->generatePdf($id, 'stream');
    }

    public function download($id)
    {
        return $this->generatePdf($id, 'download');
    }

    public function print($id)
    {
        return $this->generatePdf($id, 'stream');
    }
}