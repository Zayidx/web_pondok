<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table = 'soals';

    protected $fillable = [
        'ujian_id',
        'tipe_soal',
        'pertanyaan',
        'opsi',
        'kunci_jawaban',
    ];

    protected $casts = [
        'opsi' => 'array',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }
} 