<?php

namespace App\Models;

use App\Traits\HasMedia;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Blog extends Eloquent
{
    use HasMedia;
    protected $connection = 'mongodb';
    protected $collection = 'blogs';
    protected $guarded = [];
    protected $appends = ['image_url'];
}
