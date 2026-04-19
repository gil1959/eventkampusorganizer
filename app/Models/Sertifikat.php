<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    protected $table = 'sertifikats';
    protected $primaryKey = 'id_sertifikat';

    protected $fillable = [
        'id_pendaftaran',
        'nomor_sertifikat',
        'isi_sertifikat',
        'template_pdf',
        'file_path',
        'tanggal_terbit',
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran', 'id_pendaftaran');
    }

    public function getDownloadUrlAttribute(): string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return route('peserta.sertifikat.download', $this->id_sertifikat);
    }
}
