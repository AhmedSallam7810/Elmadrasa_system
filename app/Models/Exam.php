<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'date', 'status', 'score', 'notes'];

    protected $casts = ['date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
