<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relationship: A package has many features
     */
    public function packageFeatures()
    {
        return $this->hasMany(PackageFeature::class);
    }
}
