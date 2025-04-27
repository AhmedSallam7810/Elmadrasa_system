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

    public function calculateDegree(): int
    {
        // Absent or excused => 0
        if (in_array($this->status, ['absent', 'excused'])) {
            return 0;
        }
        // Class schedule
        $class = $this->student->classes->first();
        if (!$class) {
            return 0;
        }
        $schedule = $class->schedules()
            ->where('day_of_week', $this->date->format('N'))
            ->first();
        // Default full marks if no schedule
        if (!$schedule) {
            return $this->status === 'present' ? 30 : 15;
        }
        $classStartTime = \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' ' . $schedule->start_time);
        $attendanceTime = $this->created_at;
        // On time => 30
        if ($this->status === 'present' && $attendanceTime->diffInMinutes($classStartTime) <= 15) {
            return 30;
        }
        // Late => 15
        elseif ($this->status === 'late' && $attendanceTime->diffInMinutes($classStartTime) <= 30) {
            return 15;
        }
        return 0;
    }
}
