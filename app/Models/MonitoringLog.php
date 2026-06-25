<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringLog extends Model
{
    protected $fillable = [
        'kolam_id',
        'metode_budidaya',
        'suhu',
        'ph',
        'amonia',
        'status_panen',
        'umur_ikan',
        'kondisi_air',
        'rekomendasi',
    ];

    protected $casts = [
        'suhu' => 'decimal:2',
        'ph' => 'decimal:2',
        'amonia' => 'decimal:4',
        'umur_ikan' => 'integer',
    ];

    /**
     * Get the kolam that owns this monitoring log.
     */
    public function kolam(): BelongsTo
    {
        return $this->belongsTo(Kolam::class);
    }
}
