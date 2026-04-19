<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktors', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama', 100);
            $table->string('npm_nip', 20)->nullable()->unique()->comment('NPM untuk panitia/peserta, NIP untuk admin');
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'panitia', 'peserta'])->default('peserta');
            $table->string('no_hp', 20)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('foto')->nullable();
            $table->boolean('verifikasi')->default(false)->comment('Verifikasi panitia oleh admin');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktors');
    }
};
