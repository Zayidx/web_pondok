<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'expires_at',
    ];

    /**
     * TAMBAHKAN METHOD INI
     * Mendefinisikan bahwa satu Sesi QR (QrSession) bisa memiliki
     * banyak Log Scan (scanLogs).
     * Ini adalah relasi "one-to-many".
     */
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class);
    }
}
