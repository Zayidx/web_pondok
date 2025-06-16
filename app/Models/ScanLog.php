<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_session_id',
        'santri_id',
    ];

    /**
     * Mendefinisikan bahwa satu Log Scan milik satu Santri.
     * Relasi "belongs-to".
     */
    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Mendefinisikan bahwa satu Log Scan milik satu Sesi QR.
     * Relasi "belongs-to".
     */
    public function qrSession()
    {
        return $this->belongsTo(QrSession::class);
    }
}
