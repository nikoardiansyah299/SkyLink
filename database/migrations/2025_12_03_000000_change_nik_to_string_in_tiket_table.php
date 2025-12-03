<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to modify column to VARCHAR so we avoid requiring doctrine/dbal.
        // Adjust length to 20 to comfortably hold 16-digit NIKs and any formatting.
        DB::statement("ALTER TABLE `tiket` MODIFY `nik` VARCHAR(20) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to integer (original). Be aware this will truncate values that don't fit.
        DB::statement("ALTER TABLE `tiket` MODIFY `nik` INT NOT NULL");
    }
};
