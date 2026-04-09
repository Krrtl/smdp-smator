<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->integer('tahun_angkatan')->nullable()->comment('Tahun siswa masuk (kelas X)');
            $table->integer('tahun_update')->nullable()->comment('Tahun terakhir kelas diupdate');
            $table->boolean('is_active')->default(true)->comment('Toggle aktif/tidak aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['tahun_angkatan', 'tahun_update', 'is_active']);
        });
    }
};
