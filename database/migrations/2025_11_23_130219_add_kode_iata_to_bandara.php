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
        Schema::table('bandara', function (Blueprint $table) {
            $table->string('kode_iata', 5)->after('nama_bandara');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bandara', function (Blueprint $table) {
            $table->dropColumn('kode_iata');
        });
    }
};
