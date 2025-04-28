<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use HasFactory;

    protected $table = 'summaries';
    protected $fillable = ['student_id', 'date', 'status', 'degree', 'notes'];
    protected $casts = ['date' => 'date', 'degree' => 'float'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
