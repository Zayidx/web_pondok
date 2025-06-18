<?php

namespace App\Models\Absensi;

use App\Models\Santri;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    use HasFactory;

    protected $table = 'scan_logs';
    protected $fillable = ['qr_session_id', 'santri_id'];

    public function qrSession()
    {
        return $this->belongsTo(QrSession::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}