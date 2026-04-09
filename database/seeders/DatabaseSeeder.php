<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Jalankan UserSeeder yang sudah kamu buat
        $this->call(UserSeeder::class);
    }
}