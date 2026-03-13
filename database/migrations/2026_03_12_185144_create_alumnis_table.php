<?php
// database/migrations/xxxx_xx_xx_create_alumnis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap'); // Nama asli dari database kampus [cite: 60]
            $table->string('nim')->unique();
            $table->string('prodi');
            $table->year('tahun_lulus'); // Digunakan untuk logika Timeline (+20 poin) [cite: 71, 86]
            $table->string('status_pelacakan')->default('Belum Dilacak'); // [cite: 72, 115]
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('alumnis');
    }
};
