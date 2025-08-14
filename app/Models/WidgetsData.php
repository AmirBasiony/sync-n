<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class WidgetsData extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'widgets_data';

    protected $fillable = [
        'name',
        'type_id',
        'area_id',
        'category_id',
        'icon_id',
        'device_id',
        'attributes',
        'created_at',
        'updated_at',
        'visible',
        'production_name',
        'factory_name',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
