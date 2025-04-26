<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports form.
     */
    public function index()
    {
        $classes = SchoolClass::all();
        return view('admin.reports.index', compact('classes'));
    }

    /**
     * Generate the report based on filters.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'class_id' => 'required|exists:classes,id'
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $class = SchoolClass::findOrFail($request->class_id);

        $students = Student::whereHas('classes', function ($query) use ($class) {
            $query->where('class_id', $class->id);
        })
            ->with(['attendances' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }])
            ->with(['examResults' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($student) {
                $totalAttendance = $student->attendances->count();
                $presentAttendance = $student->attendances->where('status', 'present')->count();
                $attendanceRate = $totalAttendance > 0 ? ($presentAttendance / $totalAttendance) * 100 : 0;

                $averageScore = $student->examResults->avg('score') ?? 0;

                $student->attendance_rate = $attendanceRate;
                $student->average_score = $averageScore;

                return $student;
            });

        return view('admin.reports.show', compact('students', 'startDate', 'endDate', 'class'));
    }
}
