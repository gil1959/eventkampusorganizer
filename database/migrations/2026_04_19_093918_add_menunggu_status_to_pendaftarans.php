<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: modify enum column untuk tambah nilai 'menunggu'
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status_kehadiran ENUM('menunggu','pendaftar','terbayar','hadir','batal') NOT NULL DEFAULT 'pendaftar'");
    }

    public function down(): void
    {
        // Hapus 'menunggu' dari enum (convert record ke 'pendaftar' dulu)
        DB::statement("UPDATE pendaftarans SET status_kehadiran = 'pendaftar' WHERE status_kehadiran = 'menunggu'");
        DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status_kehadiran ENUM('pendaftar','terbayar','hadir','batal') NOT NULL DEFAULT 'pendaftar'");
    }
};
