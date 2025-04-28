<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Behavior extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'date', 'status', 'degree', 'notes'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
