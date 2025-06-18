<?php

// Mendefinisikan namespace untuk kelas ini, berada di dalam direktori App\Livewire\Absensi
namespace App\Livewire\Absensi;

// Mengimpor model-model yang diperlukan dari database
use App\Models\Absensi\Absensi; // Model untuk data absensi utama
use App\Models\Absensi\AbsensiDetail; // Model untuk detail kehadiran per santri
use App\Models\Absensi\QrSession; // Model untuk sesi QR code yang sedang aktif
use App\Models\Absensi\ScanLog; // Mengimpor model untuk mencatat setiap aktivitas scan
use Illuminate\Support\Facades\Auth; // Mengimpor facade untuk menangani otentikasi
use Livewire\Component; // Mengimpor kelas dasar dari Livewire

// Mendefinisikan kelas komponen Livewire bernama Scan
class Scan extends Component
{
    // Properti publik yang akan terikat dengan view dan URL
    public $token; // Untuk menyimpan token dari URL
    public $message; // Pesan yang akan ditampilkan ke santri (sukses/gagal)
    public $status; // Status hasil scan ('success' atau 'error')

    // Method yang dijalankan saat komponen pertama kali di-mount (dibuat)
    // Menerima $token dari parameter route
    public function mount($token)
    {
        // Menyimpan token yang diterima ke properti kelas
        $this->token = $token;
        // Memanggil method untuk memproses scan
        $this->processScan();
    }

    // Method utama untuk memproses logika scan QR
    public function processScan()
    {
        // Mencari sesi QR di database berdasarkan token yang discan
        $qrSession = QrSession::where('token', $this->token)->first();
        // Mendapatkan ID santri yang sedang login menggunakan guard 'santri'
        $santriId = Auth::guard('santri')->id();

        // Pengecekan 1: Apakah sesi QR valid?
        if (!$qrSession) {
            $this->status = 'error';
            $this->message = 'QR Code tidak valid atau tidak ditemukan.';
            return; // Menghentikan eksekusi jika tidak valid
        }

        // Pengecekan 2: Apakah sesi QR sudah kedaluwarsa?
        if (now()->greaterThan($qrSession->expires_at)) {
            $this->status = 'error';
            $this->message = 'Sesi QR Code sudah kedaluwarsa.';
            return; // Menghentikan eksekusi jika kedaluwarsa
        }

        // Mengambil data absensi utama berdasarkan absensi_id dari sesi QR
        $absensi = Absensi::find($qrSession->absensi_id);
        // Mengambil data jadwal pelajaran yang terkait dengan absensi tersebut
        $jadwal = $absensi->jadwalPelajaran;

        // Pengecekan 3: Apakah santri yang scan terdaftar di kelas yang benar?
        if (auth('santri')->user()->kelas_id != $jadwal->kelas_id) {
            $this->status = 'error';
            $this->message = 'Anda tidak terdaftar di kelas ini.';
            return; // Menghentikan eksekusi jika bukan kelasnya
        }
        
        // Pengecekan 4: Apakah santri sudah pernah scan untuk sesi QR yang sama?
        $pernahScan = ScanLog::where('qr_session_id', $qrSession->id)
            ->where('santri_id', $santriId)->exists();
            
        if($pernahScan) {
            $this->status = 'error';
            $this->message = 'Anda sudah melakukan scan dengan QR Code ini.';
            return; // Menghentikan eksekusi jika sudah pernah scan QR ini
        }
        
        // Logika Baru: Mencatat setiap percobaan scan ke tabel 'scan_logs'
        // Ini digunakan untuk menampilkan daftar scan secara real-time di halaman admin
        ScanLog::create([
            'qr_session_id' => $qrSession->id,
            'santri_id' => $santriId
        ]);

        // Logika Utama: Memperbarui atau membuat catatan di 'absensi_details'
        // Ini adalah data kehadiran final untuk santri tersebut
        AbsensiDetail::updateOrCreate(
            // Kondisi untuk mencari: berdasarkan absensi_id dan santri_id
            ['absensi_id' => $absensi->id, 'santri_id' => $santriId],
            // Data yang diisi/diupdate: status menjadi 'Hadir' dan jam hadir saat ini
            ['status' => 'Hadir', 'jam_hadir' => now()]
        );
        
        // Jika semua pengecekan lolos dan data berhasil disimpan
        $this->status = 'success';
        $this->message = 'Absensi berhasil! Anda tercatat hadir untuk mata pelajaran ' . $jadwal->mata_pelajaran . '.';
    }

    // Method untuk merender view
    public function render()
    {
        // Menampilkan view 'livewire.absensi.scan' dengan layout 'app-mobile'
        return view('livewire.absensi.scan');
    }
}