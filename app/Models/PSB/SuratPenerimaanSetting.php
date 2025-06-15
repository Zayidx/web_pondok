<?php
// File: app/Models/PSB/SuratPenerimaanSetting.php

namespace App\Models\PSB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratPenerimaanSetting extends Model
{
    // Menggunakan trait HasFactory untuk memungkinkan pembuatan model melalui factory.
    use HasFactory;

    // Menentukan nama tabel database secara eksplisit.
    protected $table = 'psb_sertifikat_templates';

    // Mendefinisikan atribut yang boleh diisi secara massal (mass assignment).
    // Ini adalah fitur keamanan untuk melindungi dari pengisian kolom yang tidak diinginkan.
    protected $fillable = [
        'nama_pesantren',
        'nama_yayasan',
        'alamat_pesantren',
        'telepon_pesantren',
        'email_pesantren',
        'logo', // Menambahkan 'logo' ke dalam fillable.
        'stempel', // Menambahkan 'stempel' ke dalam fillable.
        'catatan_penting',
        'nama_direktur',
        'nip_direktur',
        'nama_kepala_admin',
        'nip_kepala_admin',
        'tahun_ajaran',
        'tanggal_orientasi',
        'batas_pembayaran_spp',
    ];

    // Mendefinisikan casting tipe data otomatis untuk atribut tertentu.
    protected $casts = [
        // Mengonversi nilai dari kolom ini menjadi objek Carbon (tanggal) saat diambil.
        'tanggal_orientasi' => 'date',
        'batas_pembayaran_spp' => 'date',
    ];
}