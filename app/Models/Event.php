<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id_event';

    protected $fillable = [
        'nama_event',
        'deskripsi',
        'tanggal',
        'jam',
        'lokasi',
        'kuota',
        'poster',
        'status',
        'catatan_admin',
        'id_panitia',
        'id_kategori',
        'narasumber',
        'biaya',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'biaya'   => 'decimal:2',
    ];

    public function panitia()
    {
        return $this->belongsTo(Aktor::class, 'id_panitia', 'id_user');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'id_event', 'id_event');
    }

    public function pesertaTerdaftar()
    {
        return $this->pendaftarans()->whereNotIn('status_kehadiran', ['batal']);
    }

    public function getKuotaSisaAttribute(): int
    {
        return $this->kuota - $this->pesertaTerdaftar()->count();
    }

    public function getPosterUrlAttribute(): string
    {
        if ($this->poster && file_exists(public_path('storage/' . $this->poster))) {
            return asset('storage/' . $this->poster);
        }
        return asset('images/default-event.jpg');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'badge-draft',
            'menunggu'  => 'badge-warning',
            'aktif'     => 'badge-success',
            'selesai'   => 'badge-secondary',
            'ditolak'   => 'badge-danger',
            default     => 'badge-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'     => 'Draft',
            'menunggu'  => 'Menunggu Persetujuan',
            'aktif'     => 'Aktif',
            'selesai'   => 'Selesai',
            'ditolak'   => 'Ditolak',
            default     => 'Unknown',
        };
    }
}
