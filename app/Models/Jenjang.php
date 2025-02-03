<?php

namespace App\Models;

use App\Models\ESantri\JadwalPelajaran;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenjang extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
    ];

    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
