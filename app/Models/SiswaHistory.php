<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaHistory extends Model
{
    use HasFactory;

    protected $table = 'siswa_histories';
    
    protected $fillable = [
        'siswa_id',
        'kelas_lama',
        'kelas_baru',
        'status_lama',
        'status_baru',
        'tahun',
        'created_by',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
