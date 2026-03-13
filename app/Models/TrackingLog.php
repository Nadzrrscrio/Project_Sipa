<?php
// app/Models/TrackingLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id',
        'sumber_data',
        'judul_temuan',
        'bukti_snippet',
        'link_bukti',
        'score_nama',
        'score_afiliasi',
        'score_timeline',
        'total_confidence_score'
    ];

    /**
     * Relasi balik: Setiap Log merujuk pada satu Alumni.
     */
    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}
