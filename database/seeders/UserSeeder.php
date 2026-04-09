<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (idempotent)
        User::updateOrCreate(
            ['username' => 'tofan'],
            [
                'nama' => 'Mohamad Tofan Habib, M.Pd',
                'password' => Hash::make('123456'),
                'jabatan' => 'Kesiswaan',
                'role' => 'admin'
            ]
        );

        // User biasa (idempotent)
        User::updateOrCreate(
            ['username' => 'mannan'],
            [
                'nama' => 'Abd. Mannan, M.Pd',
                'password' => Hash::make('123456'),
                'jabatan' => 'Humas',
                'role' => 'user'
            ]
        );

        // Tatatertib (bidang khusus) (idempotent) - now a separate role
        User::updateOrCreate(
            ['username' => 'tatatertib'],
            [
                'nama' => 'Tata Tertib',
                'password' => Hash::make('123456'),
                'jabatan' => 'Tata Tertib',
                'role' => 'tatatertib'
            ]
        );
    }
}