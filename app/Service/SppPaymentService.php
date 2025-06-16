<?php
// app/Services/SppPaymentService.php
namespace App\Services;

use App\Models\Spp\Pembayaran;
use App\Models\Spp\PembayaranTimeline;
use Illuminate\Support\Facades\DB;

class SppPaymentService
{
    /**
     * Memproses pembayaran SPP untuk santri.
     *
     * @param array $data Data pembayaran (santri_id, amount, etc.)
     * @return Pembayaran|null
     */
    public function process(array $data): ?Pembayaran
    {
        DB::beginTransaction();

        try {
            // 1. Validasi data (bisa dilempar sebagai exception jika gagal)
            $this->validatePaymentData($data);

            // 2. Buat record pembayaran utama
            $pembayaran = Pembayaran::create([
                'santri_id' => $data['santri_id'],
                'total_bayar' => $data['amount'],
                'metode_pembayaran' => $data['method'],
                'tanggal_bayar' => now(),
                // ... kolom lainnya
            ]);

            // 3. Catat di timeline/history
            PembayaranTimeline::create([
                'pembayaran_id' => $pembayaran->id,
                'status' => 'Lunas',
                'keterangan' => 'Pembayaran diterima oleh ' . auth()->user()->name,
            ]);

            // 4. Mungkin ada langkah lain? (misal: kirim notifikasi)
            // Notification::send($pembayaran->santri->orangTua, new PembayaranBerhasilNotification($pembayaran));

            DB::commit();

            return $pembayaran;

        } catch (\Exception $e) {
            DB::rollBack();
            // Catat error atau lempar kembali
            report($e);
            return null;
        }
    }

    private function validatePaymentData(array $data)
    {
        // Logika validasi data pembayaran...
    }
}