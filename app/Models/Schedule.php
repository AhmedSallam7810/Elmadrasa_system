<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'class_id',
        'day_of_week',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'start_time' => 'time',
        'end_time' => 'time'
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
