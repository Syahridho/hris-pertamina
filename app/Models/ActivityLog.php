<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $fillable = [
        'causer_type',
        'causer_id',
        'subject_type',
        'subject_id',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // =====================
    //  RELATIONSHIPS (Polymorphic)
    // =====================

    public function causer()
    {
        return $this->morphTo();
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // =====================
    //  HELPERS
    // =====================

    /**
     * Catat activity log secara statis.
     */
    public static function record(
        string $description,
        ?Model $causer = null,
        ?Model $subject = null,
        array $properties = []
    ): self {
        return self::create([
            'description'  => $description,
            'causer_type'  => $causer ? get_class($causer) : null,
            'causer_id'    => $causer?->id,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id'   => $subject?->id,
            'properties'   => $properties,
        ]);
    }
}
