<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'study_year_id',
        'name',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function studyYear()
    {
        return $this->belongsTo(StudyYear::class);
    }

    public function awardGrades()
    {
        return $this->hasMany(StudentAwardGrade::class);
    }

    public function weeklyReports()
    {
        return $this->hasMany(WeeklyReport::class);
    }
}
