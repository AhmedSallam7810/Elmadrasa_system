<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Werd extends Model
{
    protected $fillable = [
        'student_id',
        'date',
        'status',
        'degree',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'degree' => 'integer'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
