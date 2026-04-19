<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Seminar',       'deskripsi' => 'Seminar dan talkshow akademik & profesional', 'icon' => 'presentation-line'],
            ['nama_kategori' => 'Lomba',          'deskripsi' => 'Kompetisi dan perlombaan antar mahasiswa',    'icon' => 'trophy-line'],
            ['nama_kategori' => 'Workshop',       'deskripsi' => 'Pelatihan dan workshop skill praktis',        'icon' => 'tools-line'],
            ['nama_kategori' => 'Webinar',        'deskripsi' => 'Seminar online interaktif',                   'icon' => 'global-line'],
            ['nama_kategori' => 'Konferensi',     'deskripsi' => 'Konferensi ilmiah & penelitian mahasiswa',   'icon' => 'group-line'],
            ['nama_kategori' => 'Festival',       'deskripsi' => 'Festival seni budaya dan kreativitas',        'icon' => 'music-2-line'],
            ['nama_kategori' => 'Pengabdian',     'deskripsi' => 'Program pengabdian kepada masyarakat',        'icon' => 'heart-line'],
            ['nama_kategori' => 'Olahraga',       'deskripsi' => 'Kegiatan olahraga dan kesehatan',             'icon' => 'run-line'],
        ];

        foreach ($kategoris as $k) {
            Kategori::create(array_merge($k, ['is_active' => true]));
        }
    }
}
