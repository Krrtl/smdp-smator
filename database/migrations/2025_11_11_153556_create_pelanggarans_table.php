<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kelas');
            $table->string('nama');
            $table->string('jenis');
            $table->integer('skor');
            $table->boolean('status_sanksi')->default(false); // false = belum, true = sudah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggarans');
    }
};