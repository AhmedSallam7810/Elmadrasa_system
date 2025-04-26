<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'study_term_id',
        'week_start_date',
        'week_end_date',
        'class_summary',
        'awards_summary',
        'notes'
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date'
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function studyTerm()
    {
        return $this->belongsTo(StudyTerm::class);
    }
}
