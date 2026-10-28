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
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('kategori');
            $table->string('lokasi');
            $table->string('bukti')->nullable();
            $table->string('urgensi')->default('Sedang');
            $table->boolean('is_anonim')->default(false);
            $table->string('status')->default('Menunggu Verifikasi');
            $table->text('alasan_ditolak')->nullable();
            $table->string('unit_terkait')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
