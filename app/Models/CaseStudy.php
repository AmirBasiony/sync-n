<?php

namespace App\Models;

use App\Traits\HasMedia;
use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CaseStudy extends Eloquent
{
    use HasMedia;

    protected $connection = 'mongodb';
    protected $collection = 'case_studies';
    protected $guarded = [];
    protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(CaseStudyCategory::class, 'category_id');
    }
}
