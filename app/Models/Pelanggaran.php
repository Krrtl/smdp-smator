<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kelas',
        'nama',
        'jenis',
        'skor',
        'status_sanksi', // boolean
        'keterangan',    // catatan admin
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tanggal' => 'datetime',
        'status_sanksi' => 'boolean',
    ];
}