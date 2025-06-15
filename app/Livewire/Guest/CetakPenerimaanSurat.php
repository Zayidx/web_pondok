<?php

namespace App\Livewire\Guest;

// Impor kelas-kelas yang dibutuhkan oleh komponen.
use Livewire\Component;
use App\Models\PSB\PendaftaranSantri;
use App\Models\PSB\SuratPenerimaanSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter; // Diperlukan untuk PHPDoc hint.
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

class CetakPenerimaanSurat extends Component
{
    // Menggunakan layout 'check-status' untuk halaman ini.
    #[Layout('components.layouts.check-status')]

    // Properti publik untuk menampung data dari URL dan data santri.
    public $registrationId;
    public $santri;

    /**
     * Metode 'mount' dijalankan saat komponen pertama kali di-load.
     * Fungsinya untuk inisialisasi data.
     *
     * @param int $registrationId ID pendaftaran yang didapat dari route/URL.
     */
    public function mount($registrationId)
    {
        // Mengisi properti registrationId dari parameter.
        $this->registrationId = $registrationId;

        // Mengambil data santri dari database beserta relasi 'wali' dan 'periode'.
        // Menggunakan find() untuk mencari berdasarkan primary key.
        $this->santri = PendaftaranSantri::with(['wali', 'periode'])->find($registrationId);

        // Jika data santri tidak ditemukan, hentikan proses dan tampilkan halaman 404.
        if (!$this->santri) {
            session()->flash('error', 'Data pendaftaran tidak ditemukan.');
            abort(404);
        }

        // Jika status santri BUKAN 'diterima', hentikan proses dan tampilkan halaman 403 (Akses Ditolak).
        if ($this->santri->status_santri !== 'diterima') {
            session()->flash('error', 'Surat penerimaan hanya tersedia untuk pendaftaran yang berstatus DITERIMA.');
            abort(403);
        }
    }

    /**
     * Helper function untuk mengubah path gambar menjadi format URI data Base64.
     * Versi ini tangguh untuk menangani error path dan mimetype.
     *
     * @param string|null $path Path file yang tersimpan di database.
     * @return string|null URI Data Base64 atau null jika gagal.
     */
    private function imageToBase64($path)
    {
        // Jika path dari database kosong, langsung kembalikan null.
        if (!$path) {
            return null;
        }

        // Menghapus prefix 'public/' dari path jika ada. Ini penting karena Storage::disk('public')
        // sudah mengarah ke folder 'storage/app/public'.
        $correctPath = str_replace('public/', '', $path);

        // Simpan instance disk ke dalam variabel agar lebih bersih.
        $disk = Storage::disk('public');

        // PHPDoc ini memberi tahu linter (Intelephense) bahwa variabel $disk adalah instance
        // dari FilesystemAdapter, sehingga method seperti 'mimeType' tidak akan ditandai error.
        /** @var FilesystemAdapter $disk */

        // Periksa kembali apakah file benar-benar ada di storage setelah path dikoreksi.
        if (!$disk->exists($correctPath)) {
            // Catat peringatan di log jika file tidak ada untuk memudahkan debugging.
            Log::warning('File gambar tidak ditemukan di storage: ' . $correctPath);
            return null;
        }

        // Ambil konten (data biner) dari file gambar.
        $fileData = $disk->get($correctPath);

        // Ambil ekstensi file untuk menebak MIME type. Ini cara paling aman & cepat.
        $extension = strtolower(pathinfo($correctPath, PATHINFO_EXTENSION));

        // Gunakan 'match' untuk mencocokkan ekstensi dengan MIME type yang sesuai.
        $mimeType = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            default => null, // Jika ekstensi tidak dikenali, kembalikan null.
        };

        // Jika MIME type tidak berhasil ditebak dari ekstensi, coba gunakan metode bawaan Laravel.
        if (!$mimeType) {
            try {
                // Metode ini bisa gagal jika ekstensi PHP 'fileinfo' tidak aktif di server.
                $mimeType = $disk->mimeType($correctPath);
            } catch (\Exception $e) {
                // Jika terjadi error, catat pesan error di log dan kembalikan null.
                Log::error('Gagal mendapatkan MIME type: ' . $e->getMessage());
                return null;
            }
        }

        // Gabungkan MIME type dan data gambar yang sudah di-encode ke Base64 menjadi satu string Data URI.
        return 'data:' . $mimeType . ';base64,' . base64_encode($fileData);
    }

    /**
     * Metode ini dipanggil untuk membuat dan mengunduh file PDF.
     */
    public function generateAndDownloadPdf()
    {
        // Mengambil data pengaturan surat dari database (hanya baris pertama).
        $settings = SuratPenerimaanSetting::first();

        // Jika data pengaturan belum diisi oleh admin, hentikan proses dan kembali ke halaman sebelumnya.
        if (!$settings) {
            session()->flash('error', 'Pengaturan surat belum dikonfigurasi oleh admin.');
            return redirect()->back();
        }

        // Menyiapkan array 'data' yang akan dikirimkan ke view Blade PDF.
        $data = [
            'santri' => $this->santri,
            'settings' => $settings,
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y'),
            'nomor_surat' => 'PSB/' . ($settings->tahun_ajaran ?? date('Y')) . '/P/' . str_pad($this->santri->id, 4, '0', STR_PAD_LEFT),
            // Panggil helper function untuk mengubah logo dan stempel menjadi Base64.
            'base64Logo' => $this->imageToBase64($settings->logo),
            'base64Stempel' => $this->imageToBase64($settings->stempel),
        ];

        // Memuat view 'psb.surat-penerimaan-pdf' dengan data di atas dan mengonversinya ke PDF.
        $pdf = Pdf::loadView('psb.surat-penerimaan-pdf', $data);

        // Menentukan nama file saat diunduh.
        $fileName = 'Surat_Penerimaan_' . str_replace(' ', '_', $this->santri->nama_lengkap) . '.pdf';

        // Mengirimkan PDF ke browser sebagai stream untuk diunduh.
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    /**
     * Metode 'render' yang bertanggung jawab untuk menampilkan view komponen.
     */
    public function render()
    {
        // Mengembalikan view Blade yang terkait dengan komponen ini.
        return view('livewire.guest.cetak-penerimaan-surat');
    }
}