<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Werd;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WerdsController extends Controller
{
    public function index(Request $request)
    {
        $query = Werd::with(['student.classes'])
            ->orderBy('date', 'desc');

        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');
        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', function($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('degree')) {
            $query->where('degree', $request->degree);
        }

        // Clone the query for stats to avoid pagination issues
        $statsQuery = clone $query;

        $werds = $query->paginate(10);

        // Calculate statistics using the cloned query
        $stats = [
            'total' => $statsQuery->count(),
            'good' => $statsQuery->where('status', 'good')->count(),
            'average' => $statsQuery->where('status', 'average')->count(),
            'weak' => $statsQuery->where('status', 'weak')->count(),
        ];

        // If AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('admin.werds.partials.werd-table', compact('werds', 'stats', 'date'));
        }

        // For regular request, return full view
        $classes = SchoolClass::all();
        return view('admin.werds.index', compact('werds', 'stats', 'classes', 'date'));
    }

    public function create()
    {
        $date = request('date', now()->format('Y-m-d'));
        $classId = request('class_id');

        // Get all classes for the filter dropdown
        $classes = SchoolClass::orderBy('name')->get();

        // Query builder for students
        $studentsQuery = Student::with('classes')
            ->whereDoesntHave('werds', function ($query) use ($date) {
                $query->whereDate('date', $date);
            });

        // Apply class filter if selected
        if ($classId) {
            $studentsQuery->whereHas('classes', function ($query) use ($classId) {
                $query->where('school_classes.id', $classId);
            });
        }

        // Get students
        $students = $studentsQuery->orderBy('name')->paginate(50);

        return view('admin.werds.create', compact('students', 'classes', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array',
            'status.*' => 'required|in:good,average,weak',
            'degree' => 'required|array',
            'degree.*' => 'required|numeric|min:0|max:10',
            'notes' => 'nullable|array',
            'notes.*' => 'nullable|string|max:255'
        ]);

        $date = $request->date;
        $records = [];
        $updates = [];

        foreach ($request->student_ids as $studentId) {
            $attendance = Werd::where('student_id', $studentId)
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
            Werd::insert($records);
        }

        // Bulk update existing records
        if (!empty($updates)) {
            foreach ($updates as $update) {
                Werd::where('id', $update['id'])->update([
                    'status' => $update['status'],
                    'degree' => $update['degree'],
                    'notes' => $update['notes'],
                    'updated_at' => $update['updated_at']
                ]);
            }
        }

        return redirect()
            ->route('admin.werds.index')
            ->with('success', 'Werd records have been saved successfully.');
    }

    public function show(Werd $werd)
    {
        return view('admin.werds.show', compact('werd'));
    }

    public function edit(Werd $werd)
    {
        return view('admin.werds.edit', compact('werd'));
    }

    public function update(Request $request, Werd $werd)
    {
        $request->validate([
            'status' => 'required|in:good,average,weak',
            'degree' => 'required|numeric|min:0|max:10',
            'notes' => 'nullable|string|max:255'
        ]);

        $werd->update([
            'status' => $request->status,
            'degree' => $request->degree,
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.werds.index')
            ->with('success', 'Werd record updated successfully.');
    }

    public function destroy(Werd $werd)
    {
        $werd->delete();

        return redirect()
            ->route('admin.werds.index')
            ->with('success', 'Werd record has been deleted successfully.');
    }
}
