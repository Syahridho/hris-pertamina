<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'manpower_id',
        'supervisor_id',
        'clock_in',
        'clock_out',
        'date',
        'status',
    ];

    protected $casts = [
        'clock_in'  => 'datetime',
        'clock_out' => 'datetime',
        'date'      => 'date',
    ];

    // =====================
    //  RELATIONSHIPS
    // =====================

    public function manpower(): BelongsTo
    {
        return $this->belongsTo(Manpower::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    // =====================
    //  HELPERS
    // =====================

    /**
     * Menghitung durasi kerja dalam menit.
     */
    public function getWorkDurationMinutesAttribute(): ?int
    {
        if (!$this->clock_in || !$this->clock_out) return null;
        return $this->clock_in->diffInMinutes($this->clock_out);
    }

    /**
     * Menentukan siapa pemilik rekaman absensi.
     */
    public function getEmployeeAttribute(): Manpower|Supervisor|null
    {
        return $this->manpower ?? $this->supervisor;
    }
}
