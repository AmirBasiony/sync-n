<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Notification extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'notifications';

    protected $guarded = [];
}
