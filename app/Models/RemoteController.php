<?php 
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class RemoteController extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'remote_controllers';

    protected $fillable = [
        'brand', 'model', 'type_id', 'remote_type', 'attributes'
    ];

    
}
