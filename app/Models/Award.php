<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'points',
        'class_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'points' => 'integer'
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'award_student')
            ->withPivot('is_completed', 'completed_at')
            ->withTimestamps()
            ->using(AwardStudent::class);
    }
}
