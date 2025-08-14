<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use MongoDB\Laravel\Relations\HasMany;

class User extends Eloquent implements AuthenticatableContract
{
    use Authenticatable, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';
    protected $keyType = 'string';


    protected $guarded = [];


    protected $hidden = [
        'password',
        'token',
        'activation_code',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activation_code_expires_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * ===============================
     * RELATIONSHIPS
     * ===============================
     */

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
