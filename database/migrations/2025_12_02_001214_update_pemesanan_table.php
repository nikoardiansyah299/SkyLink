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
        Schema::table('pemesanan', function (Blueprint $table) {

            // Hapus kolom tipe_kelas kalau ada
            if (Schema::hasColumn('pemesanan', 'tipe_kelas')) {
                $table->dropColumn('tipe_kelas');
            }

            // Tambah kolom total pembayaran
            $table->bigInteger('total')->default(0)->after('jumlah_tiket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {

            // Kembalikan tipe_kelas
            $table->string('tipe_kelas')->nullable();

            // Hapus total
            $table->dropColumn('total');
        });
    }
};
