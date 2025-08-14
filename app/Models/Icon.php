<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Icon extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'icons';

    protected $fillable = ['_id','name', 'type', 'src'];
}
