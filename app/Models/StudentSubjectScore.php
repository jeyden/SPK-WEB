<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSubjectScore extends Model
{
    protected $table = 'student_subject_scores';

    protected $fillable = [
        'student_id',
        'school_subject_id',
        'academic_year',
        'score',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolSubject(): BelongsTo
    {
        return $this->belongsTo(SchoolSubject::class);
    }
}