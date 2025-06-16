<?php

namespace App\Services\PSB;

use App\Models\PSB\Pengumuman;
use App\Models\PSB\Santri;
use App\Models\PSB\Periode;
use App\Services\PSB\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengumumanService
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function publishPengumuman($periodeId)
    {
        DB::beginTransaction();
        try {
            // Validasi periode
            $periode = Periode::findOrFail($periodeId);
            
            if ($periode->status_periode !== 'active') {
                throw new \Exception('Periode tidak aktif');
            }

            // Ambil semua santri yang sudah menyelesaikan proses seleksi
            $santri = Santri::where('periode_id', $periodeId)
                ->where('status_pendaftaran', 'selesai_seleksi')
                ->get();

            foreach ($santri as $s) {
                // Buat pengumuman untuk setiap santri
                $pengumuman = Pengumuman::create([
                    'santri_id' => $s->id,
                    'periode_id' => $periodeId,
                    'status_kelulusan' => $this->determineKelulusan($s),
                    'tanggal_pengumuman' => Carbon::now(),
                    'catatan' => $this->generateCatatan($s)
                ]);

                // Update status pendaftaran santri
                $s->update([
                    'status_pendaftaran' => $pengumuman->status_kelulusan === 'lulus' ? 'lulus' : 'tidak_lulus'
                ]);

                // Kirim notifikasi
                $this->sendPengumumanNotification($pengumuman);
            }

            // Update status periode
            $periode->update([
                'status_periode' => 'completed'
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error publishing pengumuman: ' . $e->getMessage());
            throw $e;
        }
    }

    private function determineKelulusan($santri)
    {
        // Implementasi logika penentuan kelulusan
        // Contoh sederhana:
        $nilaiUjian = $santri->nilai_ujian;
        $nilaiWawancara = $santri->nilai_wawancara;
        $statusPembayaran = $santri->status_pembayaran;

        if ($statusPembayaran !== 'lunas') {
            return 'tidak_lulus';
        }

        $totalNilai = ($nilaiUjian * 0.7) + ($nilaiWawancara * 0.3);
        return $totalNilai >= 70 ? 'lulus' : 'tidak_lulus';
    }

    private function generateCatatan($santri)
    {
        if ($santri->status_pembayaran !== 'lunas') {
            return 'Pembayaran belum lunas';
        }

        $nilaiUjian = $santri->nilai_ujian;
        $nilaiWawancara = $santri->nilai_wawancara;
        $totalNilai = ($nilaiUjian * 0.7) + ($nilaiWawancara * 0.3);

        if ($totalNilai < 70) {
            return 'Nilai tidak memenuhi syarat kelulusan';
        }

        return 'Selamat! Anda dinyatakan lulus seleksi PSB';
    }

    private function sendPengumumanNotification($pengumuman)
    {
        $data = [
            'nama_santri' => $pengumuman->santri->nama_lengkap,
            'status_kelulusan' => $pengumuman->status_kelulusan,
            'tanggal_pengumuman' => $pengumuman->tanggal_pengumuman,
            'catatan' => $pengumuman->catatan
        ];

        // Kirim email
        $this->notificationService->sendEmail(
            $pengumuman->santri->email,
            'Pengumuman Hasil PSB',
            'pengumuman_published',
            $data
        );

        // Kirim WhatsApp
        $message = "Pengumuman Hasil PSB\n\n" .
                  "Nama: {$pengumuman->santri->nama_lengkap}\n" .
                  "Status: " . ucfirst($pengumuman->status_kelulusan) . "\n" .
                  "Tanggal: {$pengumuman->tanggal_pengumuman}\n" .
                  "Catatan: {$pengumuman->catatan}";

        $this->notificationService->sendWhatsApp(
            $pengumuman->santri->no_hp,
            $message
        );
    }

    public function getPengumumanBySantri($santriId)
    {
        try {
            return Pengumuman::where('santri_id', $santriId)
                ->with(['santri', 'periode'])
                ->latest()
                ->first();
        } catch (\Exception $e) {
            Log::error('Error getting pengumuman: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getPengumumanByPeriode($periodeId)
    {
        try {
            return Pengumuman::where('periode_id', $periodeId)
                ->with(['santri', 'periode'])
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting pengumuman: ' . $e->getMessage());
            throw $e;
        }
    }
} 