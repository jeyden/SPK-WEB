<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nisn',
        'class',
        'academic_year',
        'high_school_major',
        'interest',
        'economy',
        'gender',
        'phone',
        'address',
        'avatar',
        'profile_completed',
    ];

    // Relasi
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(StudentAssessment::class, 'student_id');
    }

    /**
     * Alias relasi untuk mendukung pemanggilan studentAssessments
     */
    public function studentAssessments(): HasMany
    {
        return $this->hasMany(StudentAssessment::class, 'student_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(RecommendationResult::class, 'student_id');
    }
   
    public function riasecScore(): HasOne
    {
        return $this->hasOne(RiasecScore::class, 'student_id');
    }
    
    public function subjectScores(): HasMany
    {
        return $this->hasMany(StudentSubjectScore::class, 'student_id');
    }
}