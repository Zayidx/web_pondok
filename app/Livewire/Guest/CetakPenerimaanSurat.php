<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use App\Models\PSB\PendaftaranSantri; // Pastikan model PendaftaranSantri sudah diimpor
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan Dompdf Facade sudah diimpor
use Carbon\Carbon; // Pastikan Carbon diimpor untuk penanganan tanggal
use Illuminate\Support\Facades\Log; // Untuk debugging
use Livewire\Attributes\Layout;

class CetakPenerimaanSurat extends Component
{
    #[Layout('components.layouts.check-status')]
    public $registrationId; // Properti untuk menampung ID pendaftaran dari URL
    public $santri; // Properti untuk menampung objek santri

    // Metode mount akan dijalankan saat komponen pertama kali diinisiasi
    public function mount($registrationId)
    {
        $this->registrationId = $registrationId;
        
        // Mengambil data santri beserta relasi yang dibutuhkan untuk surat
        // Menggunakan 'wali' (sesuai definisi di model PendaftaranSantri) dan 'periode'.
        // 'jenjang' tidak lagi dimuat sebagai relasi karena itu adalah kolom langsung 'nama_jenjang'.
        $this->santri = PendaftaranSantri::with(['wali', 'periode'])->find($registrationId); // Perbaikan di sini

        if (!$this->santri) {
            session()->flash('error', 'Data pendaftaran tidak ditemukan.'); // Flash message
            Log::warning('Santri not found for certificate generation', ['registration_id' => $registrationId]);
            abort(404); // Menghentikan eksekusi dan menampilkan halaman 404
        }

        if ($this->santri->status_santri !== 'diterima') {
            session()->flash('error', 'Surat penerimaan hanya tersedia untuk pendaftaran yang berstatus DITERIMA.'); // Flash message
            Log::warning('Attempted to access certificate for santri not in "diterima" status', [
                'santri_id' => $this->santri->id,
                'current_status' => $this->santri->status_santri
            ]);
            abort(403); // Akses ditolak
        }

        Log::info('CetakPenerimaanSurat component mounted successfully', [
            'santri_id' => $this->santri->id,
            'status_santri' => $this->santri->status_santri,
        ]);
    }

    // Metode ini akan dipanggil saat tombol unduh di halaman ini diklik.
    // Metode ini akan langsung menghasilkan dan mengunduh PDF.
    public function generateAndDownloadPdf()
    {
        Log::info('generateAndDownloadPdf method triggered in Livewire component.');

        // Validasi ulang data santri dan statusnya untuk keamanan
        if (!$this->santri || $this->santri->status_santri !== 'diterima') {
            Log::warning('Invalid santri data or status for PDF generation during download request.', [
                'santri_id' => $this->santri->id ?? 'N/A',
                'current_status' => $this->santri->status_santri ?? 'N/A'
            ]);
            session()->flash('error', 'Surat penerimaan tidak dapat diunduh. Pastikan status pendaftaran sudah DITERIMA.');
            return; // Hentikan eksekusi
        }

        // Siapkan data yang akan digunakan di view PDF
        $data = [
            'santri' => $this->santri,
            'tanggal_cetak' => Carbon::now()->translatedFormat('d F Y'), // Tanggal cetak sekarang
            // Mengakses nama_jenjang langsung dari objek santri karena itu adalah kolom.
            'jenjang_diterima' => $this->santri->nama_jenjang ?? 'Jenjang tidak tersedia', // Perbaikan di sini
            'periode_pendaftaran' => $this->santri->periode->nama_periode ?? 'Periode tidak tersedia',
            'acceptanceDate' => $this->santri->updated_at ? $this->santri->updated_at->translatedFormat('d F Y') : Carbon::now()->translatedFormat('d F Y'),
            'issueDate' => Carbon::now()->translatedFormat('d F Y'),
            'certificateNumber' => 'CERT/' . date('Y') . '/' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT), // Contoh nomor sertifikat
        ];

        // Memuat view Blade ke Dompdf. Pastikan path view ini benar.
        // Anda perlu membuat file resources/views/psb/surat-penerimaan-pdf.blade.php
        $pdf = Pdf::loadView('psb.surat-penerimaan-pdf', $data);

        // Tentukan nama file PDF yang akan diunduh
        $fileName = 'Surat_Penerimaan_' . str_replace(' ', '_', $this->santri->nama_lengkap) . '.pdf';

        Log::info('Initiating PDF download.', [
            'santri_id' => $this->santri->id,
            'file_name' => $fileName,
            'certificate_number' => $data['certificateNumber'],
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $fileName);
    }

    public function render()
    {
        return view('livewire.guest.cetak-penerimaan-surat'); 
    }
}