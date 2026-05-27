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
    ];

    protected $casts = [
        'type'   => 'string', // ENUM: cuti, sakit, izin
        'status' => 'string', // ENUM: pending, approved, rejected
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
}
