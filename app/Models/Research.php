<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Research extends Model
{
    /**
     * Explicit table name matching migration
     */
    protected $table = 'researchs';

    use HasFactory;

    protected $fillable = ['student_id', 'date', 'status', 'degree', 'notes'];
    protected $casts = ['date' => 'date', 'degree' => 'float'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
