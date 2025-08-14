<?php
namespace App\Models;

use Illuminate\Support\Carbon;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class Type extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'types';

    protected $fillable = [
        '_id',
        'device_id',
        'name',
        'description',
    ];
    
    protected $casts = [
        '_id' => 'string',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d - g:i A');
    }

    // public function getTypesAttribute()
    // {
    //     if (is_array($this->type_ids) && count($this->type_ids) > 0) {
    //         return Type::whereIn('_id', $this->type_ids)->get()->map(function ($type) {
    //             $type->subtypes = Subtype::where('type_id', $type->_id)->get();
    //             return $type;
    //         });
    //     }
    //     return collect([]);
    // }

    public function device()
    {
        return $this->hasMany(Device::class, 'device_id', '_id');
    }
}
