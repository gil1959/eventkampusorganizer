<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->foreignId('id_event')->constrained('events', 'id_event')->onDelete('cascade');
            $table->foreignId('id_peserta')->constrained('aktors', 'id_user')->onDelete('cascade');
            $table->timestamp('tanggal_daftar')->useCurrent();
            $table->enum('status_kehadiran', ['pendaftar', 'terbayar', 'hadir', 'batal'])->default('pendaftar');
            $table->string('nomor_tiket')->unique()->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Satu peserta hanya bisa daftar satu kali per event
            $table->unique(['id_event', 'id_peserta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
