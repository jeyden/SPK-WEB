<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolSubject extends Model
{
    protected $table = 'school_subjects';

    protected $fillable = [
        'major_target',
        'name',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(StudentSubjectScore::class);
    }
}