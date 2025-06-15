<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    // Menentukan nama tabel eksplisit. Ini HARUS sesuai dengan nama tabel di migrasi.
    protected $table = 'soals'; 

    // Mendefinisikan atribut yang dapat diisi secara massal.
    protected $fillable = [
        'ujian_id',
        'tipe_soal',
        'pertanyaan',
        'opsi',
        'kunci_jawaban',
        'poin',
    ];

    // Mendefinisikan casting untuk atribut
    protected $casts = [
        'opsi' => 'array',
        'kunci_jawaban' => 'string', // Ubah dari 'integer' menjadi 'string'
        'poin' => 'integer',
    ];

    // Mendefinisikan konstanta untuk tipe soal
    const TIPE_PG = 'pg';
    const TIPE_ESSAY = 'essay';

    /**
     * Mengembalikan opsi tipe soal yang tersedia.
     * @return array
     */
    public static function getTipeOptions()
    {
        return [
            self::TIPE_PG => 'Pilihan Ganda',
            self::TIPE_ESSAY => 'Essay',
        ];
    }

    /**
     * Mendefinisikan relasi "satu soal termasuk dalam satu ujian".
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ujian()
    {
        // Soal milik satu Ujian
        // Pastikan model Ujian berada di namespace yang benar (App\Models\PSB\Ujian)
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }
}
