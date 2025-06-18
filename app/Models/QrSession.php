<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrSession extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'qr_sessions';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'absensi_id',
        'token',
        'expires_at',
    ];

    /**
     * Relasi belongs-to: Satu QrSession milik satu sesi Absensi.
     */
    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    /**
     * Relasi one-to-many: Satu QrSession dapat memiliki banyak ScanLog.
     */
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class);
    }
}
