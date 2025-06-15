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

    /**
     * Accessor untuk atribut 'bobot'.
     *
     * Metode ini memungkinkan Anda mengakses `$soal->bobot` di kode Anda,
     * dan jika `tipe_soal` adalah 'essay', nilai yang dikembalikan adalah dari kolom 'poin'.
     * Ini mengatasi masalah 'Unknown column 'bobot'' tanpa mengubah Livewire component
     * yang sudah ada yang menggunakan `$soal->bobot`.
     *
     * @return float|null
     */
    public function getBobotAttribute(): ?float
    {
        // Jika tipe soal adalah 'essay', kembalikan nilai dari kolom 'poin'.
        if ($this->tipe_soal === self::TIPE_ESSAY) {
            return (float) $this->poin;
        }
        // Untuk tipe soal pilihan ganda, 'bobot' sudah didefinisikan di dalam array 'opsi'.
        // Accessor ini hanya untuk memastikan properti 'bobot' tersedia secara konsisten.
        return null; // Mengembalikan null jika properti 'bobot' tidak relevan secara langsung dari kolom 'poin'
    }
}
