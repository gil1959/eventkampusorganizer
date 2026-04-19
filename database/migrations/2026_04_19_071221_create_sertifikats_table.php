<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id('id_sertifikat');
            $table->foreignId('id_pendaftaran')->constrained('pendaftarans', 'id_pendaftaran')->onDelete('cascade');
            $table->string('nomor_sertifikat', 50)->unique();
            $table->text('isi_sertifikat')->nullable();
            $table->string('template_pdf', 50)->default('default');
            $table->string('file_path')->nullable();
            $table->timestamp('tanggal_terbit')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
