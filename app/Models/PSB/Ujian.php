<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import tambahan yang akan dibutuhkan untuk relasi
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PSB\HasilUjian;

class Ujian extends Model
{
    // Menggunakan trait HasFactory untuk memungkinkan pembuatan data dummy (seeder).
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     * Penting untuk didefinisikan jika nama tabel tidak mengikuti konvensi Laravel
     * (misalnya, jika model 'Ujian' tabelnya bukan 'ujians').
     * @var string
     */
    protected $table = 'ujians';

    /**
     * Atribut yang diizinkan untuk diisi secara massal (mass assignment).
     * Ini adalah lapisan keamanan untuk mencegah pengisian kolom yang tidak diinginkan.
     * @var array
     */
    protected $fillable = [
        'ujian_id',
        'nama_ujian',
        'mata_pelajaran',
        'tanggal_ujian',
        'waktu_mulai',
        'waktu_selesai',
        'periode_id',
        'status_ujian',
    ];

    /**
     * Tipe data asli dari atribut-atribut model.
     * Laravel akan secara otomatis mengubah (cast) atribut ini ke tipe yang ditentukan
     * saat diakses, membuatnya lebih mudah untuk dimanipulasi.
     * @var array
     */
    protected $casts = [
        'tanggal_ujian' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Mendefinisikan relasi 'belongsTo' ke model Periode.
     * Ini menandakan bahwa setiap record Ujian "milik" satu record Periode.
     */
    public function periode()
    {
        // Parameter kedua ('periode_id') adalah foreign key pada tabel 'ujians'.
        return $this->belongsTo(\App\Models\PSB\Periode::class, 'periode_id');
    }

    /**
     * Mendefinisikan relasi 'hasMany' ke model Soal.
     * Ini menandakan bahwa satu Ujian dapat "memiliki banyak" Soal.
     */
    public function soals(): HasMany
    {
        // Foreign key 'ujian_id' di tabel 'soals' akan digunakan untuk mencocokkan relasi.
        return $this->hasMany(\App\Models\PSB\Soal::class, 'ujian_id');
    }

    // --- DI SINI ANDA PERLU MENAMBAHKAN RELASI YANG HILANG ---
    // Tambahkan method hasilUjians() di bawah ini.
    public function hasilUjians(): HasMany
    {
        // Laravel akan secara otomatis mencari foreign key 'ujian_id'
        // di dalam tabel yang terhubung dengan model HasilUjian.
        return $this->hasMany(HasilUjian::class);
    }
}
