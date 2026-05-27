<?php

namespace App\Models;

use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Superadmin extends Authenticatable implements HasName
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'superadmins';

    protected $fillable = [
        'username',
        'email',
        'no_telp',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Filament user name contract.
     */
    public function getFilamentName(): string
    {
        return $this->username ?? '';
    }

    /**
     * Virtual name attribute.
     */
    public function getNameAttribute(): string
    {
        return $this->username ?? '';
    }
}
