<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekening_panitias', function (Blueprint $table) {
            $table->id('id_rekening');
            $table->foreignId('id_event')->constrained('events', 'id_event')->onDelete('cascade');
            $table->enum('tipe', ['bank', 'ewallet'])->default('bank');
            $table->string('nama_bank', 100)->comment('Nama bank / e-wallet, contoh: BCA, GoPay');
            $table->string('nomor_rekening', 50);
            $table->string('atas_nama', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekening_panitias');
    }
};
