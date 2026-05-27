<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    // =====================
    //  RELATIONSHIPS
    // =====================

    public function supervisors(): HasMany
    {
        return $this->hasMany(Supervisor::class);
    }

    public function manpowers(): HasMany
    {
        return $this->hasMany(Manpower::class);
    }

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }
}
