<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasilUjian extends Model
{
    use HasFactory;

    protected $table = 'hasil_ujians';

    protected $fillable = [
        'santri_id',
        'ujian_id',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'nilai', // Ini kemungkinan adalah total_skor_pg awal
        'nilai_akhir', // Ini akan menyimpan total nilai keseluruhan (PG + Esai)
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(PendaftaranSantri::class, 'santri_id');
    }

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function jawabanUjians(): HasMany
    {
        return $this->hasMany(JawabanUjian::class, 'hasil_ujian_id');
    }
}