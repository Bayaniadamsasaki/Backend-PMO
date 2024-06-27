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
        Schema::create('laporan_medis', function (Blueprint $table) {
            $table->id();
            $table->string('foto');
            $table->string('jenis');
            $table->string('nama');
            $table->string('telepon');
            $table->string('lokasi');
            $table->string('tanggal');
            $table->string('isi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_medis');
    }
};
