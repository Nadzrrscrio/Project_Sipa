<?php
// database/migrations/xxxx_xx_xx_create_tracking_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tracking_logs', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel alumni (Setiap temuan harus merujuk ke satu profil target) [cite: 32, 100]
            $table->foreignId('alumni_id')->constrained('alumnis')->onDelete('cascade');

            $table->string('sumber_data'); // Misal: LinkedIn, Google Scholar, dll [cite: 13, 20]
            $table->string('judul_temuan'); // Judul profil atau publikasi yang ditemukan [cite: 108, 110]
            $table->text('bukti_snippet'); // Snippet informasi identitas yang diekstrak [cite: 108, 128]
            $table->string('link_bukti'); // Pointer bukti (URL) [cite: 66, 128]

            // Kolom untuk Logika Scoring [cite: 68, 113]
            $table->integer('score_nama')->default(0);     // Max +40 [cite: 69, 86]
            $table->integer('score_afiliasi')->default(0); // Max +40 [cite: 70, 86]
            $table->integer('score_timeline')->default(0); // Max +20 [cite: 71, 86]
            $table->integer('total_confidence_score');      // Total skor untuk klasifikasi [cite: 72, 89]

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tracking_logs');
    }
};
