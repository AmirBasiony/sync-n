<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Site extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'sites';

    protected $guarded = [];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
