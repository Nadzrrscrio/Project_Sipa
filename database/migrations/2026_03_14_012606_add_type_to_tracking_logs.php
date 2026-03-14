<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('tracking_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('tracking_logs', 'snippet')) {
                $table->text('snippet')->nullable()->after('link_bukti');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tracking_logs', function (Blueprint $table) {
            //
        });
    }
};
