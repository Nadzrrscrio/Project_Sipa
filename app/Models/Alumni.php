<?php
// app/Models/Alumni.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    // Menentukan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nama_lengkap',
        'nim',
        'prodi',
        'tahun_lulus',
        'status_pelacakan'
    ];

    /**
     * Relasi: Satu Alumni bisa memiliki banyak Jejak Bukti (Tracking Logs)
     * Sesuai alur 'Simpan Riwayat & Bukti Temuan' di Use Case.
     */
    public function trackingLogs()
    {
        return $this->hasMany(TrackingLog::class);
    }
}
