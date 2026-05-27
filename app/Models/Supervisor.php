<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Supervisor extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'supervisors';

    protected $fillable = [
        'username',
        'email',
        'no_telp',
        'password',
        'project_id',
        'placement_id',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // =====================
    //  RELATIONSHIPS
    // =====================

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    /**
     * Virtual name attribute.
     */
    public function getNameAttribute(): string
    {
        return $this->username ?? '';
    }
}
