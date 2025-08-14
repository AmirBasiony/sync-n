<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d - g:i A');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPlanAttribute()
    {
        return Package::find($this->package_id);
    }
}
