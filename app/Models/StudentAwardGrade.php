<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAwardGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'award_id',
        'study_term_id',
        'study_year_id',
        'grade',
        'notes'
    ];

    protected $casts = [
        'grade' => 'integer'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function award()
    {
        return $this->belongsTo(Award::class);
    }

    public function studyTerm()
    {
        return $this->belongsTo(StudyTerm::class);
    }

    public function studyYear()
    {
        return $this->belongsTo(StudyYear::class);
    }
}
