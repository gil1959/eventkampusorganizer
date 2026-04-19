<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id('id_event');
            $table->string('nama_event', 150);
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->string('lokasi', 200)->nullable();
            $table->integer('kuota')->default(0);
            $table->string('poster')->nullable();
            $table->enum('status', ['draft', 'menunggu', 'aktif', 'selesai', 'ditolak'])->default('draft');
            $table->text('catatan_admin')->nullable()->comment('Catatan dari admin saat approve/reject');
            $table->foreignId('id_panitia')->constrained('aktors', 'id_user')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('kategoris', 'id_kategori')->onDelete('restrict');
            $table->string('narasumber')->nullable();
            $table->decimal('biaya', 10, 2)->default(0)->comment('Biaya pendaftaran (0 = gratis)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
