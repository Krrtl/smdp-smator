<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

protected $fillable = [
    'user_id',
    'nama_dokumen',
    'jenis',
    'tahun',
    'file_path',
    'bidang',
];

public function user()
{
    return $this->belongsTo(User::class);
}

}