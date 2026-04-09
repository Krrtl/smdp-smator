<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('jenis')->nullable(); // opsional: bisa diisi "PDF", "Word", dll
            $table->year('tahun');
            $table->string('file_path');
            $table->string('bidang'); // otomatis dari user yang login
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};