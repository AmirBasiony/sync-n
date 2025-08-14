<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Widgets extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'widgets';

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    
}
