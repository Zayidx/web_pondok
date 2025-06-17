<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'scan_logs';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'qr_session_id',
        'santri_id',
    ];

    /**
     * Relasi belongs-to: Satu ScanLog milik satu Santri.
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relasi belongs-to: Satu ScanLog milik satu Sesi QR.
     */
    public function qrSession()
    {
        return $this->belongsTo(QrSession::class);
    }
}
