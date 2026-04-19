<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningPanitia extends Model
{
    protected $table = 'rekening_panitias';
    protected $primaryKey = 'id_rekening';

    protected $fillable = [
        'id_event',
        'tipe',
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event', 'id_event');
    }

    public function getTipeIconAttribute(): string
    {
        return $this->tipe === 'ewallet' ? 'ri-wallet-line' : 'ri-bank-line';
    }

    public function getTipeLabelAttribute(): string
    {
        return $this->tipe === 'ewallet' ? 'E-Wallet' : 'Bank Transfer';
    }
}
