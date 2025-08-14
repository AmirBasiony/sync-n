<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Area extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'areas';

    protected $guarded = [];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
