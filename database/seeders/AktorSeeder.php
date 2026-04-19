<?php

namespace Database\Seeders;

use App\Models\Aktor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AktorSeeder extends Seeder
{
    public function run(): void
    {
        // Admin (menggunakan NIP)
        Aktor::create([
            'nama'      => 'Administrator Kampus',
            'npm_nip'   => '198501012010011001',
            'email'     => 'admin@eventkampus.id',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'verifikasi' => true,
            'is_active' => true,
        ]);

        // Panitia 1 (sudah terverifikasi)
        Aktor::create([
            'nama'      => 'Budi Santoso',
            'npm_nip'   => '2021101001',
            'email'     => 'budi@eventkampus.id',
            'password'  => Hash::make('panitia123'),
            'role'      => 'panitia',
            'jurusan'   => 'Teknik Informatika',
            'no_hp'     => '081234567890',
            'verifikasi' => true,
            'is_active' => true,
        ]);

        // Panitia 2 (belum terverifikasi)
        Aktor::create([
            'nama'      => 'Siti Rahayu',
            'npm_nip'   => '2021101002',
            'email'     => 'siti@eventkampus.id',
            'password'  => Hash::make('panitia123'),
            'role'      => 'panitia',
            'jurusan'   => 'Sistem Informasi',
            'no_hp'     => '082345678901',
            'verifikasi' => false,
            'is_active' => true,
        ]);

        // Peserta 1
        Aktor::create([
            'nama'      => 'Ahmad Fauzi',
            'npm_nip'   => '2022201001',
            'email'     => 'ahmad@eventkampus.id',
            'password'  => Hash::make('peserta123'),
            'role'      => 'peserta',
            'jurusan'   => 'Teknik Informatika',
            'no_hp'     => '083456789012',
            'verifikasi' => true,
            'is_active' => true,
        ]);

        // Peserta 2
        Aktor::create([
            'nama'      => 'Dewi Lestari',
            'npm_nip'   => '2022201002',
            'email'     => 'dewi@eventkampus.id',
            'password'  => Hash::make('peserta123'),
            'role'      => 'peserta',
            'jurusan'   => 'Manajemen Bisnis',
            'no_hp'     => '084567890123',
            'verifikasi' => true,
            'is_active' => true,
        ]);

        // Peserta 3
        Aktor::create([
            'nama'      => 'Rizki Pratama',
            'npm_nip'   => '2022201003',
            'email'     => 'rizki@eventkampus.id',
            'password'  => Hash::make('peserta123'),
            'role'      => 'peserta',
            'jurusan'   => 'Hukum',
            'verifikasi' => true,
            'is_active' => true,
        ]);
    }
}
