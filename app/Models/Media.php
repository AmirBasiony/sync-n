<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Media extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'media';

    protected $fillable = [
        'file_name', 'path', 'collection', 'disk',
        'modelable_id', 'modelable_type',
    ];

    public function modelable()
    {
        return $this->morphTo();
    }
}
