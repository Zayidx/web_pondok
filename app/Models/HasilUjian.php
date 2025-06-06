<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PSB\PendaftaranSantri;

class HasilUjian extends Model
{
    protected $table = 'hasil_ujians';

    protected $fillable = [
        'santri_id',
        'ujian_id',
        'waktu_mulai',
        'waktu_selesai',
        'total_skor',
        'nilai',
        'status'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'total_skor' => 'integer',
        'nilai' => 'decimal:2'
    ];

    /**
     * Get the santri that owns the hasil ujian
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }

    /**
     * Get the ujian that owns the hasil ujian
     */
    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }
} 