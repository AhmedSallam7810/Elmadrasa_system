<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassStudent extends Pivot
{
    protected $table = 'class_student';

    protected $fillable = [
        'class_id',
        'student_id',
        'enrolled_at',
        'is_active'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
