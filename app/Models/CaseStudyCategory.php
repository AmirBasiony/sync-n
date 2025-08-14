<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as Eloquent;

class CaseStudyCategory extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'case_study_categories';
    protected $guarded = [];
    public function caseStudies()
    {
        return $this->hasMany(CaseStudy::class, 'category_id');
    }
}
