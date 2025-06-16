<?php

namespace App\Services\PSB;

use App\Models\PSB\Pembayaran;
use App\Models\PSB\BiayaDaftarUlang;
use App\Services\PSB\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function createPembayaran($santriId, $jenisPembayaran, $nominal, $metodePembayaran, $buktiPembayaran = null)
    {
        DB::beginTransaction();
        try {
            // Validasi nominal
            $biaya = BiayaDaftarUlang::where('jenis_biaya', $jenisPembayaran)
                ->where('status', 'active')
                ->first();

            if (!$biaya) {
                throw new \Exception('Jenis pembayaran tidak valid');
            }

            if ($nominal < $biaya->nominal) {
                throw new \Exception('Nominal pembayaran kurang dari yang ditentukan');
            }

            // Generate nomor pembayaran
            $nomorPembayaran = $this->generateNomorPembayaran($jenisPembayaran);

            // Buat record pembayaran
            $pembayaran = Pembayaran::create([
                'santri_id' => $santriId,
                'nomor_pembayaran' => $nomorPembayaran,
                'jenis_pembayaran' => $jenisPembayaran,
                'nominal' => $nominal,
                'metode_pembayaran' => $metodePembayaran,
                'bukti_pembayaran' => $buktiPembayaran,
                'status' => 'pending',
                'tanggal_pembayaran' => Carbon::now()
            ]);

            // Kirim notifikasi
            $this->sendPaymentNotification($pembayaran);

            DB::commit();
            return $pembayaran;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating payment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function verifyPembayaran($pembayaranId, $adminId, $catatan = null)
    {
        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::findOrFail($pembayaranId);

            if ($pembayaran->status !== 'pending') {
                throw new \Exception('Status pembayaran tidak valid untuk diverifikasi');
            }

            // Update status pembayaran
            $pembayaran->update([
                'status' => 'verified',
                'verified_by' => $adminId,
                'verified_at' => Carbon::now(),
                'catatan' => $catatan
            ]);

            // Update status pendaftaran jika semua pembayaran sudah lunas
            $this->updateStatusPendaftaran($pembayaran->santri_id);

            // Kirim notifikasi verifikasi
            $this->sendVerificationNotification($pembayaran);

            DB::commit();
            return $pembayaran;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error verifying payment: ' . $e->getMessage());
            throw $e;
        }
    }

    public function rejectPembayaran($pembayaranId, $adminId, $alasan)
    {
        DB::beginTransaction();
        try {
            $pembayaran = Pembayaran::findOrFail($pembayaranId);

            if ($pembayaran->status !== 'pending') {
                throw new \Exception('Status pembayaran tidak valid untuk ditolak');
            }

            // Update status pembayaran
            $pembayaran->update([
                'status' => 'rejected',
                'verified_by' => $adminId,
                'verified_at' => Carbon::now(),
                'catatan' => $alasan
            ]);

            // Kirim notifikasi penolakan
            $this->sendRejectionNotification($pembayaran);

            DB::commit();
            return $pembayaran;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting payment: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateNomorPembayaran($jenisPembayaran)
    {
        $prefix = strtoupper(substr($jenisPembayaran, 0, 3));
        $date = Carbon::now()->format('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$date}-{$random}";
    }

    private function updateStatusPendaftaran($santriId)
    {
        // Cek apakah semua pembayaran sudah lunas
        $totalBiaya = BiayaDaftarUlang::where('status', 'active')->sum('nominal');
        $totalPembayaran = Pembayaran::where('santri_id', $santriId)
            ->where('status', 'verified')
            ->sum('nominal');

        if ($totalPembayaran >= $totalBiaya) {
            // Update status pendaftaran menjadi 'lunas'
            // Implementasi sesuai dengan model dan struktur database
        }
    }

    private function sendPaymentNotification($pembayaran)
    {
        $data = [
            'nomor_pembayaran' => $pembayaran->nomor_pembayaran,
            'jenis_pembayaran' => $pembayaran->jenis_pembayaran,
            'nominal' => $pembayaran->nominal,
            'metode_pembayaran' => $pembayaran->metode_pembayaran,
            'tanggal_pembayaran' => $pembayaran->tanggal_pembayaran
        ];

        $this->notificationService->sendEmail(
            $pembayaran->santri->email,
            'Pembayaran PSB',
            'payment_created',
            $data
        );
    }

    private function sendVerificationNotification($pembayaran)
    {
        $data = [
            'nomor_pembayaran' => $pembayaran->nomor_pembayaran,
            'jenis_pembayaran' => $pembayaran->jenis_pembayaran,
            'nominal' => $pembayaran->nominal,
            'tanggal_verifikasi' => $pembayaran->verified_at
        ];

        $this->notificationService->sendEmail(
            $pembayaran->santri->email,
            'Verifikasi Pembayaran PSB',
            'payment_verified',
            $data
        );
    }

    private function sendRejectionNotification($pembayaran)
    {
        $data = [
            'nomor_pembayaran' => $pembayaran->nomor_pembayaran,
            'jenis_pembayaran' => $pembayaran->jenis_pembayaran,
            'nominal' => $pembayaran->nominal,
            'alasan' => $pembayaran->catatan
        ];

        $this->notificationService->sendEmail(
            $pembayaran->santri->email,
            'Pembayaran PSB Ditolak',
            'payment_rejected',
            $data
        );
    }
} 