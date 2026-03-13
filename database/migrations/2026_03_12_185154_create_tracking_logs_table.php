<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tracking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumnis')->onDelete('cascade');

            $table->string('sumber_data');
            $table->string('judul_temuan');
            $table->text('bukti_snippet');
            $table->string('link_bukti');


            $table->integer('score_nama')->default(0);
            $table->integer('score_afiliasi')->default(0);
            $table->integer('score_timeline')->default(0);
            $table->integer('total_confidence_score');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tracking_logs');
    }
};
