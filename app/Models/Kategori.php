<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';

    public function getRouteKeyName(): string
    {
        return 'id_kategori';
    }

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'id_kategori', 'id_kategori');
    }

    public function getEventsCountAttribute(): int
    {
        return $this->events()->count();
    }
}
