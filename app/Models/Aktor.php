<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Aktor extends Authenticatable
{
    use Notifiable;

    protected $table = 'aktors';
    protected $primaryKey = 'id_user';

    public function getRouteKeyName(): string
    {
        return 'id_user';
    }

    protected $fillable = [
        'nama',
        'npm_nip',
        'email',
        'password',
        'role',
        'no_hp',
        'jurusan',
        'foto',
        'verifikasi',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'verifikasi'  => 'boolean',
        'is_active'   => 'boolean',
    ];

    // Label identifikasi sesuai role
    public function getLabelIdentifikasiAttribute(): string
    {
        return $this->role === 'admin' ? 'NIP' : 'NPM';
    }

    // Relasi: panitia memiliki banyak event
    public function events()
    {
        return $this->hasMany(Event::class, 'id_panitia', 'id_user');
    }

    // Relasi: peserta memiliki banyak pendaftaran
    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_peserta', 'id_user');
    }

    // Helper checks
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPanitia(): bool
    {
        return $this->role === 'panitia';
    }

    public function isPeserta(): bool
    {
        return $this->role === 'peserta';
    }
}
