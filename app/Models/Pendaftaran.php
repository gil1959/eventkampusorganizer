<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftarans';
    protected $primaryKey = 'id_pendaftaran';

    protected $fillable = [
        'id_event',
        'id_peserta',
        'tanggal_daftar',
        'status_kehadiran',
        'nomor_tiket',
        'catatan',
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    public function peserta()
    {
        return $this->belongsTo(Aktor::class, 'id_peserta', 'id_user');
    }

    public function sertifikat()
    {
        return $this->hasOne(Sertifikat::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status_kehadiran) {
            'menunggu'  => 'Menunggu Konfirmasi',
            'pendaftar' => 'Terdaftar',
            'terbayar'  => 'Terbayar',
            'hadir'     => 'Hadir',
            'batal'     => 'Dibatalkan',
            default     => 'Unknown',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_kehadiran) {
            'menunggu'  => 'badge-warning',
            'pendaftar' => 'badge-success',
            'terbayar'  => 'badge-navy',
            'hadir'     => 'badge-success',
            'batal'     => 'badge-danger',
            default     => 'badge-secondary',
        };
    }
}
