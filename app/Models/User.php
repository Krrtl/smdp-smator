<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara massal (mass assignable)
     */
    protected $fillable = [
        'nama',
        'username',
        'password',
        'jabatan',
        'role',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (misal ke JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Konversi otomatis tipe data
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}