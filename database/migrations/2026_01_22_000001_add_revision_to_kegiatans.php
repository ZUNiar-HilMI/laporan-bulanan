<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->text('catatan_revisi')->nullable()->after('status');
        });

        // Update enum to include 'revision' status
        DB::statement("ALTER TABLE kegiatans MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'revision') DEFAULT 'pending'");
    }

    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('catatan_revisi');
        });

        DB::statement("ALTER TABLE kegiatans MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
