<?php

namespace App\Services\Services;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use App\Services\Contracts\AttendanceContract;
use Illuminate\Http\Request;

class AttendanceService implements AttendanceContract
{
    protected $attendance_repository;
    public function __construct(AttendanceRepositoryInterface $attendance_repository)
    {
        $this->attendance_repository = $attendance_repository;
    }

    public function getAttendanceData($request)
    {
        $filters = $request->all();
        $query = $this->attendance_repository->filter($filters);

        $attendances = $query->latest()->get();
        $classes = SchoolClass::all();
        $date = !empty($filters['date']) ? $filters['date'] : now()->format('Y-m-d');

        //------------------total_students------------------
        $students_data = Student::query();

        if ($request->filled('class_id')) {
            $students_data->whereHas('classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        $total_students = $students_data->count();
        //------------------total_students------------------

        //------------------other stats------------------

        $attendence_data = Attendance::query();

        if ($request->filled('class_id')) {
            $attendence_data->whereHas('student.classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

            $attendence_data->where('date', $request->date);

        //------------------other stats------------------


        $total = $attendence_data->count();
        $present = $attendence_data->get()->where('status', 'present')->count();
        $absent = $attendence_data->get()->where('status', 'absent')->count();
        $late = $attendence_data->get()->where('status', 'late')->count();
        $stats = [
            'total_students' => $total_students,
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
        ];
        return compact('attendances', 'classes', 'date', 'stats');
    }

    public function createAttendance()
    {
        $date = request('date', now()->format('Y-m-d'));
        $classId = request('class_id');

        // Get students who don't have attendance records for the selected date
        $query = Student::with(['classes', 'attendances' => function ($query) use ($date) {
            $query->whereDate('date', $date);
        }])
            ->whereDoesntHave('attendances', function ($query) use ($date) {
                $query->whereDate('date', $date);
            });

        if ($classId) {
            $query->whereHas('classes', function ($q) use ($classId) {
                $q->where('classes.id', $classId);
            });
        }

        $students = $query->get();
        $classes = SchoolClass::all();
    }
}
