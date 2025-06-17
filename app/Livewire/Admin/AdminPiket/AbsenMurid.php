<?php

namespace App\Livewire\Admin\AdminPiket;

use App\Models\Absensi;
use App\Models\AbsensiDetail;
use App\Models\ESantri\JadwalPelajaran;
use App\Models\QrSession;
use App\Models\Santri;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

class AbsenMurid extends Component
{
    public JadwalPelajaran $jadwal;
    public $qrCodeUrl;
    public $qrSession;
    public $sessionExpiresAt;

    public Collection $liveScans;
    public Collection $semuaSantri;
    public array $statusKehadiran = [];
    public $absensiId;

    public function mount($jadwalId)
    {
        // Komentar: Mengambil data jadwal pelajaran beserta relasi kelas.
        $this->jadwal = JadwalPelajaran::with('kelas')->findOrFail($jadwalId);
        $this->liveScans = collect();

        // Komentar: Mengambil atau membuat rekaman absensi utama untuk hari ini.
        $absensi = Absensi::firstOrCreate(
            [
                'tanggal' => now()->format('Y-m-d'),
                'jadwal_pelajaran_id' => $this->jadwal->id,
            ],
            [
                'kelas_id' => $this->jadwal->kelas_id,
            ]
        );
        $this->absensiId = $absensi->id;
        
        // Komentar: Mengambil semua santri di kelas tersebut untuk ditampilkan di daftar.
        $this->semuaSantri = Santri::where('kelas_id', $this->jadwal->kelas_id)->orderBy('nama')->get();

        // Komentar: Menginisialisasi status kehadiran awal untuk setiap santri.
        foreach ($this->semuaSantri as $santri) {
            $detail = AbsensiDetail::firstOrCreate(
                [
                    'absensi_id' => $this->absensiId,
                    'santri_id' => $santri->id,
                ]
            );
            $this->statusKehadiran[$santri->id] = $detail->status;
        }
    }

    /**
     * Komentar: Membuat QR Code baru saat tombol ditekan.
     */
    public function generateNewQrCode()
    {
        // Komentar: Hapus sesi QR lama jika ada untuk memulai sesi baru.
        QrSession::where('absensi_id', $this->absensiId)->delete();
        
        $token = Str::random(40);
        $this->sessionExpiresAt = now()->addMinutes(5);

        // Komentar: Membuat sesi QR baru yang terhubung ke rekaman absensi utama.
        $this->qrSession = QrSession::create([
            'absensi_id' => $this->absensiId,
            'token' => $token,
            'expires_at' => $this->sessionExpiresAt,
        ]);

        // FIX: Menggunakan nama rute 'santri.absensi' yang benar sesuai file rute Anda.
        $this->qrCodeUrl = route('santri.absensi', ['token' => $token]);
    }
    
    /**
     * Komentar: Memeriksa status scan secara berkala.
     */
    public function checkScanStatus()
    {
        // Logika ini sudah sesuai dengan struktur database baru,
        // namun saya akan perbaiki sedikit agar lebih efisien.
        if (!$this->absensiId) {
            return;
        }
            
        // Komentar: Update status di tabel utama berdasarkan data terbaru dari database.
        $latestDetails = AbsensiDetail::where('absensi_id', $this->absensiId)
            ->where('status', 'Hadir')
            ->with('santri')
            ->get();
            
        // Komentar: Perbarui data untuk tabel live scan.
        $this->liveScans = $latestDetails->sortByDesc('updated_at');

        // Komentar: Perbarui array status kehadiran untuk tabel daftar lengkap.
        foreach ($latestDetails as $detail) {
            if (isset($this->statusKehadiran[$detail->santri_id])) {
                $this->statusKehadiran[$detail->santri_id] = 'Hadir';
            }
        }
    }

    /**
     * Komentar: Mengupdate status kehadiran seorang santri dari sisi admin.
     */
    public function updateStatus($santriId, $status)
    {
        // Komentar: Mencari detail absensi yang spesifik.
        $detail = AbsensiDetail::where('absensi_id', $this->absensiId)
            ->where('santri_id', $santriId)
            ->first();

        if($detail) {
            // Komentar: Update status dan jam hadir di database.
            $detail->update([
                'status' => $status,
                'jam_hadir' => ($status === 'Hadir' && is_null($detail->jam_hadir)) ? now() : null,
            ]);

            // Komentar: Update properti komponen untuk tampilan real-time.
            $this->statusKehadiran[$santriId] = $status;
        }
    }

    public function render()
    {
        // Komentar: Cek apakah sesi QR sudah kedaluwarsa untuk disembunyikan.
        if ($this->sessionExpiresAt && now()->greaterThan($this->sessionExpiresAt)) {
            $this->qrCodeUrl = null; 
        }
        return view('livewire.admin.admin-piket.absen-murid');
    }
}
