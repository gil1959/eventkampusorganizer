<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pendaftaran')->constrained('pendaftarans', 'id_pendaftaran')->onDelete('cascade');
            $table->enum('metode', ['bayar_lokasi', 'transfer'])->default('transfer');
            $table->unsignedBigInteger('id_rekening')->nullable()->comment('Rekening tujuan yang dipilih peserta');
            $table->string('bank_atau_ewallet', 100)->nullable();
            $table->string('nomor_tujuan', 50)->nullable();
            $table->string('atas_nama', 100)->nullable();
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->string('bukti_transfer')->nullable()->comment('Path file bukti bayar');
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'ditolak'])->default('menunggu');
            $table->text('catatan_panitia')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->unsignedBigInteger('dikonfirmasi_oleh')->nullable();
            $table->timestamp('dikonfirmasi_at')->nullable();
            $table->timestamps();

            $table->foreign('dikonfirmasi_oleh')->references('id_user')->on('aktors')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
