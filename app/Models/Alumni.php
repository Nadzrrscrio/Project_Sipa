<?php
// app/Models/Alumni.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'nim',
        'prodi',
        'tahun_lulus',
        'status_pelacakan'
    ];

    public function trackingLogs()
    {
        return $this->hasMany(TrackingLog::class);
    }
}
