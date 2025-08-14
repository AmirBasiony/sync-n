<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relationship: A feature belongs to a package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
