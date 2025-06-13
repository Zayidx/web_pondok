<?php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan ini

class PengaturanDaftarUlang extends Model
{
    use HasFactory; // Aktifkan HasFactory

    // Model ini harus menunjuk ke tabel yang benar di database
    protected $table = 'psb_rekening_settings';
    
    protected $fillable = [
        'nama_bank', // Diubah dari 'bank' agar sesuai dengan migrasi 'psb_rekening_settings'
        'nomor_rekening',
        'atas_nama',
        'catatan_transfer', // Tambahkan karena kini ada di migrasi
        'is_active', // Tambahkan karena ada di migrasi dan mungkin diisi
    ];
}