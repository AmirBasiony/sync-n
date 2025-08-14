<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Faqs extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'faqs';
    protected $guarded = [];
}
