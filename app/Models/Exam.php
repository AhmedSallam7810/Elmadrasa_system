<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'total_score',
        'passing_score',
        'exam_date',
        'class_id',
        'is_active'
    ];

    protected $casts = [
        'total_score' => 'float',
        'passing_score' => 'float',
        'exam_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }
}
