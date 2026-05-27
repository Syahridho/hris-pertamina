<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Placement extends Model
{
    use HasFactory;

    protected $table = 'placements';

    protected $fillable = [
        'name',
        'description',
        'coordinate',
        'radius',
        'schedule_id',
        'project_id',
    ];

    protected $casts = [
        'radius' => 'integer',
    ];

    // =====================
    //  RELATIONSHIPS
    // =====================

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function supervisors(): HasMany
    {
        return $this->hasMany(Supervisor::class);
    }

    public function manpowers(): HasMany
    {
        return $this->hasMany(Manpower::class);
    }

    // =====================
    //  HELPERS
    // =====================

    /**
     * Mengambil latitude dari field coordinate.
     */
    public function getLatitudeAttribute(): ?float
    {
        if (!$this->coordinate) return null;
        return (float) explode(',', $this->coordinate)[0];
    }

    /**
     * Mengambil longitude dari field coordinate.
     */
    public function getLongitudeAttribute(): ?float
    {
        if (!$this->coordinate) return null;
        return (float) explode(',', $this->coordinate)[1];
    }
}
