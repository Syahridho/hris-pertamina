<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'name',
        'description',
        'datetimes',
    ];

    protected $casts = [
        // Auto encode/decode JSON: ['monday' => ['clock_in' => '08:00', 'clock_out' => '17:00'], ...]
        'datetimes' => 'array',
    ];

    // =====================
    //  RELATIONSHIPS
    // =====================

    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }
}
