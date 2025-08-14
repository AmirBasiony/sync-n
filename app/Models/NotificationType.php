<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class NotificationType extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'notification_types';

    protected $guarded = [];
}
 