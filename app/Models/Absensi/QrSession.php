<?php

namespace App\Models\Absensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrSession extends Model
{
    use HasFactory;
    protected $table = 'qr_sessions';
    protected $fillable = ['absensi_id', 'token', 'expires_at'];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    // Tambahkan relasi ini
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class);
    }
}