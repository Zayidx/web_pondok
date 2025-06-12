<?php

namespace App\Models\PSB; // Ini yang benar

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Ini lebih baik

class JawabanUjian extends Model
{
    use HasFactory;

    protected $table = 'jawaban_ujians';

    protected $fillable = [
        'hasil_ujian_id',
        'soal_id',
        'jawaban',
        // Pastikan 'nilai' juga ada di sini jika Anda menggunakannya untuk esai
        'nilai'
    ];

    public function hasilUjian(): BelongsTo // Explicit return type
    {
        return $this->belongsTo(HasilUjian::class, 'hasil_ujian_id'); // Explicit foreign key
    }

    public function soal(): BelongsTo // Explicit return type
    {
        return $this->belongsTo(Soal::class, 'soal_id'); // Explicit foreign key
    }
}