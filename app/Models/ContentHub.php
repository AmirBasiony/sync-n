<?php

namespace App\Models;

use App\Traits\HasMedia;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class ContentHub extends Eloquent
{
    use HasMedia;
    protected $connection = 'mongodb';
    protected $collection = 'content_hup';
    protected $guarded = [];
    protected $appends = ['image_url'];

}
