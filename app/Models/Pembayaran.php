<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pendaftaran',
        'metode',
        'id_rekening',
        'bank_atau_ewallet',
        'nomor_tujuan',
        'atas_nama',
        'jumlah',
        'bukti_transfer',
        'status',
        'catatan_panitia',
        'tanggal_bayar',
        'dikonfirmasi_oleh',
        'dikonfirmasi_at',
    ];

    protected $casts = [
        'tanggal_bayar'   => 'datetime',
        'dikonfirmasi_at' => 'datetime',
        'jumlah'          => 'decimal:2',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function dikonfirmasiOleh()
    {
        return $this->belongsTo(Aktor::class, 'dikonfirmasi_oleh', 'id_user');
    }

    public function rekening()
    {
        return $this->belongsTo(RekeningPanitia::class, 'id_rekening', 'id_rekening');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu'     => 'Menunggu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'ditolak'      => 'Ditolak',
            default        => 'Unknown',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'menunggu'     => 'badge-warning',
            'dikonfirmasi' => 'badge-success',
            'ditolak'      => 'badge-danger',
            default        => 'badge-secondary',
        };
    }

    public function getMetodeLabelAttribute(): string
    {
        return $this->metode === 'bayar_lokasi' ? 'Bayar di Lokasi' : 'Transfer';
    }
}
