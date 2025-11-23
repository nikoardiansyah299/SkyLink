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
        Schema::create('penerbangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_maskapai');
            $table->string('gambar');
            $table->integer('harga');
            $table->foreignId('id_bandara_asal')->constrained('bandara')->onDelete('cascade');
            $table->foreignId('id_bandara_tujuan')->constrained('bandara')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_berangkat');
            $table->time('jam_tiba');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerbangan');
    }
};
