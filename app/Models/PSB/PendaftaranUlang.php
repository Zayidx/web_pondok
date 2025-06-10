<?php

namespace App\Models\PSB;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranUlang extends Model
{
    protected $table = 'psb_pendaftaran_ulang';
    
    protected $fillable = [
        'siswa_id',
        'nominal_transfer',
        'tanggal_transfer',
        'bank_pengirim',
        'nama_pengirim',
        'bukti_transfer',
        'keterangan',
        'status',
        'verified_by',
        'verified_at',
        'catatan_verifikasi'
    ];

    protected $casts = [
        'tanggal_transfer' => 'date',
        'verified_at' => 'datetime',
        'nominal_transfer' => 'decimal:2'
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
} 