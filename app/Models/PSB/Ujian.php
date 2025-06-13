<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    // Menggunakan trait HasFactory untuk pabrik model
    use HasFactory;

    // Menentukan nama tabel eksplisit. Ini HARUS sesuai dengan nama tabel di migrasi (ujians).
    // Ini adalah salah satu penyebab utama 'Column not found' jika Laravel mencoba tabel 'ujian'
    // padahal tabel sebenarnya adalah 'ujians'.
    protected $table = 'ujians'; 

    // Mendefinisikan atribut yang dapat diisi secara massal.
    // Kolom-kolom ini HARUS ada dalam daftar $fillable agar dapat diisi melalui
    // metode seperti Model::create() atau Model::update().
    protected $fillable = [
        'nama_ujian',         // Kolom yang Anda coba masukkan
        'mata_pelajaran',     // Kolom yang Anda coba masukkan
        'tanggal_ujian',      // Kolom yang Anda coba masukkan
        'waktu_mulai',        // Kolom yang Anda coba masukkan
        'waktu_selesai',      // Kolom yang Anda coba masukkan
        'periode_id',         // Kolom yang Anda coba masukkan
        'status_ujian',       // Pastikan ini juga ada jika diisi di seeder
    ];

    // Mendefinisikan casting untuk atribut tanggal dan waktu
    // Ini membantu Laravel mengelola format data tanggal/waktu dengan benar.
    protected $casts = [
        'tanggal_ujian' => 'date',   
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // Mendefinisikan relasi dengan model Periode
    // Ini mengindikasikan bahwa sebuah ujian 'belongs to' satu periode.
    public function periode()
    {
        // Pastikan model 'Periode' berada di namespace yang benar atau diimpor.
        return $this->belongsTo(\App\Models\PSB\Periode::class, 'periode_id');
    }

    // Catatan: Relasi 'santri()' tidak relevan di model 'Ujian' ini karena
    // 'santri_id' tidak ada di tabel 'ujians'. Relasi ini seharusnya ada
    // di model 'HasilUjian' yang menghubungkan ujian dengan santri.
}
