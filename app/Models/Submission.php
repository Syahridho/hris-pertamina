<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    protected $table = 'submissions';

    protected $fillable = [
        'name',
        'description',
        'type',
        'manpower_id',
        'supervisor_id',
        'file',
        'status',
        'start_date',
        'end_date',
        'total_days',
    ];

    protected $casts = [
        'type'   => 'string', // ENUM: cuti, sakit, izin
        'status' => 'string', // ENUM: pending, approved, rejected
        'start_date' => 'date',
        'end_date' => 'date',
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
     * Menentukan siapa pengaju: manpower atau supervisor.
     */
    public function getSubmitterAttribute(): Manpower|Supervisor|null
    {
        return $this->manpower ?? $this->supervisor;
    }

    /**
     * Menentukan label type pengajuan.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'cuti'  => 'Cuti Tahunan',
            'sakit' => 'Sakit',
            'izin'  => 'Izin Khusus',
            default => ucfirst($this->type),
        };
    }

    /**
     * Format the date range in Indonesian style.
     */
    public function getFormattedDateRangeAttribute(): string
    {
        if (!$this->start_date) {
            return '-';
        }
        
        $start = $this->start_date->isoFormat('D MMMM Y');
        if (!$this->end_date || $this->start_date->equalTo($this->end_date)) {
            return $start;
        }
        
        $end = $this->end_date->isoFormat('D MMMM Y');
        return "{$start} - {$end}";
    }
}
