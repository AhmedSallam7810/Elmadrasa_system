<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyYear extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function terms()
    {
        return $this->hasMany(StudyTerm::class);
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_study_year')
            ->withTimestamps();
    }

    public function awardGrades()
    {
        return $this->hasMany(StudentAwardGrade::class);
    }
}
