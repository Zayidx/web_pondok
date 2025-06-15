<?php
// File: app/Http/Controllers/PSB/SuratPenerimaanController.php

namespace App\Http\Controllers\PSB;

// Mengimpor kelas-kelas yang diperlukan.
use App\Http\Controllers\Controller;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\SuratPenerimaanSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // Impor Carbon untuk manajemen tanggal.

class SuratPenerimaanController extends Controller
{
    /**
     * Helper function untuk mengubah path file gambar menjadi format URI data Base64.
     * @param string|null $path Path file dari database.
     * @return string|null String Base64 atau null jika gagal.
     */
    private function getBase64Image($path)
    {
        // Jika path dari database kosong, langsung hentikan fungsi.
        if (!$path) return null;
        
        // Membersihkan path dari prefix 'public/'.
        $correctPath = str_replace('public/', '', $path);

        // Memeriksa apakah file benar-benar ada di dalam disk 'public'.
        if (!Storage::disk('public')->exists($correctPath)) {
            Log::warning('File gambar untuk PDF tidak ditemukan di storage: ' . $correctPath);
            return null;
        }

        try {
            // Membaca data biner dari file dan menebak tipe MIME-nya.
            $fileData = Storage::disk('public')->get($correctPath);
            $extension = strtolower(pathinfo($correctPath, PATHINFO_EXTENSION));
            $mime = match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                default => 'application/octet-stream',
            };
            // Menggabungkan menjadi format Data URI.
            return "data:{$mime};base64," . base64_encode($fileData);
        } catch (\Exception $e) {
            Log::error('Gagal membaca file untuk konversi Base64: ' . $e->getMessage());
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
            // Menggunakan exception atau redirect agar lebih jelas.
            abort(500, 'Pengaturan surat penerimaan belum dikonfigurasi oleh admin.');
        }

        // --- MENYIAPKAN SEMUA DATA DINAMIS UNTUK VIEW ---

        // 1. Gambar Logo dan Stempel dalam format Base64.
        $settings->logo_base64 = $this->getBase64Image($settings->logo);
        $settings->stempel_base64 = $this->getBase64Image($settings->stempel);
        
        // 2. Data turunan dari santri dan settings.
        $periode_pendaftaran = $santri->periode->nama_periode ?? $settings->tahun_ajaran;
        $jenjang_diterima = $santri->nama_jenjang ?? 'Tidak Diketahui';

        // 3. Memproses 'catatan_penting' dari string menjadi array.
        // Setiap baris baru akan menjadi satu item dalam daftar.
        $catatan_list = isset($settings->catatan_penting) ? explode("\n", $settings->catatan_penting) : [];

        // 4. Membuat nomor surat dan tanggal.
        $nomor_surat = 'PSB/' . ($settings->tahun_ajaran ?? date('Y')) . '/P/' . str_pad($santri->id, 4, '0', STR_PAD_LEFT);
        $tanggal_terbit = Carbon::now()->translatedFormat('d F Y');
        // Tempat terbit bisa juga ditambahkan di settings jika perlu.
        $tempat_terbit = 'Jakarta';

        // 5. Mengumpulkan semua variabel ke dalam satu array untuk dikirim ke view.
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

        // Aksi default adalah menampilkan di browser.
        return $pdf->stream('surat-penerimaan-' . $santri->nama_lengkap . '.pdf');
    }

    /**
     * Menampilkan preview surat penerimaan (stream ke browser).
     */
    public function preview($id)
    {
        // Memanggil fungsi utama dengan aksi 'stream'.
        return $this->generatePdf($id, 'stream');
    }

    /**
     * Mengunduh surat penerimaan dalam format PDF.
     */
    public function download($id)
    {
        // Memanggil fungsi utama dengan aksi 'download'.
        return $this->generatePdf($id, 'download');
    }

    /**
     * Mencetak surat penerimaan (sama dengan preview/stream).
     */
    public function print($id)
    {
        // Memanggil fungsi utama dengan aksi 'stream'.
        return $this->generatePdf($id, 'stream');
    }
}