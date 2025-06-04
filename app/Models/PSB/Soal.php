<?php

namespace App\Models\PSB;

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
        'poin',
    ];

    protected $casts = [
        'opsi' => 'array',
        'kunci_jawaban' => 'integer',
        'poin' => 'integer',
    ];

    const TIPE_PG = 'pg';
    const TIPE_ESSAY = 'essay';

    public static function getTipeOptions()
    {
        return [
            self::TIPE_PG => 'Pilihan Ganda',
            self::TIPE_ESSAY => 'Essay',
        ];
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }
}