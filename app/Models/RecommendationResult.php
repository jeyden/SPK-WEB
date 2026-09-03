<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecommendationResult extends Model
{
    protected $fillable = [
        'student_id',
        'major_id',
        'academic_year',
        'preference_score',
        'rank',
        'tsk',
        'final_campus_id',
    ];

    protected function casts(): array
    {
        return [
            'preference_score' => 'decimal:4',
            'rank' => 'integer',
            'tsk' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function finalCampus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'final_campus_id');
    }

    public function scopeMainRecommendation($query)
    {
        return $query->where('rank', 1);
    }
}