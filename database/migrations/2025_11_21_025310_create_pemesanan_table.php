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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_penerbangan')->constrained('penerbangan')->onDelete('cascade');
            $table->string('kode')->unique();
            $table->integer('jumlah_tiket');
            $table->enum('tipe_kelas', ['ekonomi', 'ekonomi premium', 'bisnis', 'first class']);
            $table->enum('status', ['Confirmed', 'Cancelled', 'Pending']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
