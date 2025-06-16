<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BiayaDaftarUlang extends Model
{
    use HasFactory;

    // Nama tabel ini HARUS sesuai dengan nama tabel di migrasi (psb_rincian_biaya).
    protected $table = 'psb_rincian_biaya';
    
    // Sesuaikan fillable dengan nama kolom di database. 'nominal' diubah menjadi 'jumlah'.
    protected $fillable = [
        'nama_biaya',
        'jumlah',        // Diubah dari 'nominal'
        'keterangan',
        'is_active',
        'tahun_ajaran',  // Pastikan kolom ini juga ada dan diisi di seeder/livewire
    ];

    // Sesuaikan casting dengan nama kolom di database. 'nominal' diubah menjadi 'jumlah'.
    protected $casts = [
        'jumlah' => 'decimal:2', // Diubah dari 'nominal'
        'is_active' => 'boolean'
    ];

    // Sesuaikan method getTotalBiaya() untuk menjumlahkan kolom 'jumlah'.
    public static function getTotalBiaya()
    {
        return self::where('is_active', true)->sum('jumlah'); // Diubah dari 'nominal'
    }
}
