<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\SchoolClass;
use App\Services\Contracts\AttendanceContract;
use App\Services\Services\AttendanceService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{

    protected $attendanceService;

    public function __construct(AttendanceContract $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }


    public function index(Request $request)
    {
        $attendance_data = $this->attendanceService->getAttendanceData($request);

        if ($request->ajax()) {
            return view('admin.attendances.partials.attendance-table', $attendance_data);
        }

        return view('admin.attendances.index', $attendance_data);
    }



    public function create()
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

        return view('admin.attendances.create', compact('students', 'classes', 'date'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array',
            'status.*' => 'required|in:present,absent,late,excused',
            'degree' => 'required|array',
            'degree.*' => 'required|numeric|min:0|max:10',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:255'
        ]);


        $date = $request->date;
        $records = [];
        $updates = [];

        foreach ($request->student_ids as $studentId) {
            $attendance = Attendance::where('student_id', $studentId)
                ->whereDate('date', $date)
                ->first();

            if ($attendance) {
                // Update existing record
                $updates[] = [
                    'id' => $attendance->id,
                    'status' => $request->status[$studentId],
                    'degree' => $request->degree[$studentId],
                    'notes' => $request->notes[$studentId] ?? null,
                    'updated_at' => now()
                ];
            } else {
                // Create new record
                $records[] = [
                    'student_id' => $studentId,
                    'date' => $date,
                    'status' => $request->status[$studentId],
                    'degree' => $request->degree[$studentId],
                    'notes' => $request->notes[$studentId] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        // Bulk insert new records
        if (!empty($records)) {
            Attendance::insert($records);
        }

        // Bulk update existing records
        if (!empty($updates)) {
            foreach ($updates as $update) {
                Attendance::where('id', $update['id'])->update([
                    'status' => $update['status'],
                    'degree' => $update['degree'],
                    'notes' => $update['notes'],
                    'updated_at' => $update['updated_at']
                ]);
            }
        }

        return redirect()
            ->route('admin.attendances.index')
            ->with('success', 'Attendance records have been saved successfully.');
    }

    public function show(Attendance $attendance)
    {
        return view('admin.attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        return view('admin.attendances.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'notes' => 'nullable|string|max:255'
        ]);

        $attendance->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        // Recalculate degree based on new status
        $attendance->degree = $attendance->calculateDegree();
        $attendance->save();

        return redirect()->route('admin.attendances.index')
            ->with('success', 'Attendance record updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('admin.attendances.index')
            ->with('success', 'Attendance record has been deleted successfully.');
    }

    public function bulkCreate(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:class_rooms,id',
            'date' => 'required|date'
        ]);

        $class = ClassRoom::with('students')->findOrFail($request->class_id);
        $date = Carbon::parse($request->date);

        return view('admin.attendances.bulk-create', compact('class', 'date'));
    }
}
