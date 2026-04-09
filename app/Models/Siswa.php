<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa'; // nama tabel kamu
    protected $fillable = ['nama', 'kelas', 'status', 'tahun_angkatan', 'tahun_update', 'is_active'];

    public function histories()
    {
        return $this->hasMany(SiswaHistory::class);
    }
}