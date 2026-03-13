<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nim')->unique();
            $table->string('prodi');
            $table->year('tahun_lulus');
            $table->string('status_pelacakan')->default('Belum Dilacak');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('alumnis');
    }
};
