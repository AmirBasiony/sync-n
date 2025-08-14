<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model as Eloquent;
use App\Models\Type;

class Device extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'devices';

    protected $guarded = [];
    
    protected $appends = ['types'];
    
    protected $casts = [
        'widget_ids' => 'array',
    ];

    public function getLinkedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d - g:i A');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d - g:i A');
    }

    public function getTypesAttribute()
    {
        return Type::whereIn('_id', $this->type_ids ?? [])->get()->map(function ($type) {
            $type->subtypes = WidgetsData::where('type_id', $type->_id)->get();
            return $type;
        });
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function widgetsData()
    {
        return $this->hasMany(WidgetsData::class);
    }    

    
    public function widgetsByType()
    {
        return $this->hasMany(\App\Models\Widgets::class, 'type_id', 'type_id');
    }

}
