<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'degree',
        'notes'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'degree' => 'decimal:2'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'excused' => 'info',
            default => 'secondary'
        };
    }

    public function calculateDegree()
    {
        // If student is absent or excused, return 0
        if (in_array($this->status, ['absent', 'excused'])) {
            return 0;
        }

        // Get the class schedule for the student
        $class = $this->student->classes->first();
        if (!$class) {
            return 0;
        }

        // Get the class start time from the schedule
        $schedule = $class->schedules()
            ->where('day_of_week', $this->date->format('N'))
            ->first();

        // If no schedule found for this day, give full marks for present
        if (!$schedule) {
            return $this->status === 'present' ? 10 : 5;
        }

        $classStartTime = \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' ' . $schedule->start_time);
        $attendanceTime = $this->created_at;

        // If student is present and on time (within 15 minutes of start time)
        if ($this->status === 'present' && $attendanceTime->diffInMinutes($classStartTime) <= 15) {
            return 10;
        }
        // If student is late (between 15 and 30 minutes)
        elseif ($this->status === 'late' && $attendanceTime->diffInMinutes($classStartTime) <= 30) {
            return 5;
        }

        return 0;
    }
}
