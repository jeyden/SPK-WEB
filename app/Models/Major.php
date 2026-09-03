<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MajorCriteria;

class Major extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'field_of_study_id',
        'name',
        'degree',
        'description',
        'prospects',
    ];

    public function fieldOfStudy(): BelongsTo
    {
        return $this->belongsTo(FieldOfStudy::class, 'field_of_study_id');
    }

    public function criteriaProfiles(): HasMany
    {
        return $this->hasMany(MajorCriteria::class);
    }

    public function recommendationResults(): HasMany
    {
        return $this->hasMany(RecommendationResult::class);
    }

    public function campuses(): BelongsToMany
    {
        return $this->belongsToMany(Campus::class, 'campus_majors')
            ->using(CampusMajor::class)
            ->withPivot(['required_school_major', 'weight_score', 'accreditation', 'quota'])
            ->withTimestamps();
    }

    public function criteria(): HasOne
    {
        return $this->hasOne(MajorCriteria::class, 'major_id');
    }
}