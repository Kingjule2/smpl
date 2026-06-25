<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kolam extends Model
{
    protected $fillable = [
        'user_id',
        'nama_kolam',
        'volume_kolam',
        'tgl_tebar',
        'metode_budidaya',
        'jenis_kolam',
        'status_kolam',
        'tgl_masuk',
    ];

    protected $casts = [
        'volume_kolam' => 'decimal:2',
        'tgl_tebar' => 'date',
        'tgl_masuk' => 'date',
    ];

    /**
     * Get the user that owns this kolam.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all monitoring logs for this kolam.
     */
    public function monitoringLogs(): HasMany
    {
        return $this->hasMany(MonitoringLog::class);
    }

    /**
     * Batas hari untuk masing-masing jenis kolam.
     */
    public function getBatasHariAttribute(): int
    {
        return match ($this->jenis_kolam) {
            'bibit' => 20,
            'pembesaran' => 40,
            'finishing' => 80,
            default => 100,
        };
    }

    /**
     * Hitung umur ikan di kolam ini (hari sejak tgl_masuk atau tgl_tebar).
     */
    public function getUmurHariAttribute(): int
    {
        $tanggalAcuan = $this->tgl_masuk ?? $this->tgl_tebar;
        if (!$tanggalAcuan) {
            return 0;
        }
        return (int) Carbon::parse($tanggalAcuan)->diffInDays(Carbon::now());
    }

    /**
     * Cek apakah ada parameter air yang tidak normal dari log monitoring terakhir.
     */
    public function getHasAlertAttribute(): bool
    {
        $latestLog = $this->monitoringLogs()->orderBy('created_at', 'desc')->first();
        if (!$latestLog) {
            return false;
        }
        return $latestLog->kondisi_air === 'bahaya';
    }

    /**
     * Auto status: hitung warna/status otomatis berdasarkan umur ikan & parameter air.
     *
     * Returns: 'kosong', 'terisi', 'alert', 'siap_pindah'
     */
    public function getAutoStatusAttribute(): string
    {
        // Kolam kosong
        if ($this->status_kolam === 'kosong') {
            return 'kosong';
        }

        // Cek alert parameter air
        if ($this->has_alert) {
            return 'alert';
        }

        // Cek umur ikan vs batas hari
        $umur = $this->umur_hari;
        if ($umur >= $this->batas_hari) {
            return 'siap_pindah';
        }

        return 'terisi';
    }

    /**
     * Get CSS shape class berdasarkan jenis kolam.
     * Segitiga = bibit, Lingkaran = pembesaran, Kotak = finishing
     */
    public function getShapeAttribute(): string
    {
        return match ($this->jenis_kolam) {
            'bibit' => 'triangle',
            'pembesaran' => 'circle',
            'finishing' => 'square',
            default => 'square',
        };
    }

    /**
     * Get CSS color class berdasarkan auto_status.
     */
    public function getColorAttribute(): string
    {
        return match ($this->auto_status) {
            'kosong' => 'pond-red',
            'terisi' => 'pond-green',
            'alert' => 'pond-yellow',
            'siap_pindah' => 'pond-blue',
            default => 'pond-red',
        };
    }

    /**
     * Label status untuk ditampilkan di UI.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->auto_status) {
            'kosong' => 'Kosong',
            'terisi' => 'Terisi',
            'alert' => '⚠️ Alert Parameter',
            'siap_pindah' => $this->jenis_kolam === 'finishing' ? 'Siap Panen' : 'Siap Pindah',
            default => 'Unknown',
        };
    }

    /**
     * Label jenis kolam.
     */
    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_kolam) {
            'bibit' => '🔺 Kolam Bibit',
            'pembesaran' => '⭕ Kolam Pembesaran',
            'finishing' => '⬛ Kolam Finishing',
            default => $this->jenis_kolam,
        };
    }
}
