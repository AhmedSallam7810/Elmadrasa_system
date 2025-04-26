<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quraan;
use App\Models\Student;
use App\Models\Classes;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Quraan::with(['student', 'student.classes']);

        // Set default date to today if not provided
        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');

        // Apply date filter
        $query->whereDate('date', $date);

        // Apply class filter
        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply degree filter
        if ($request->filled('degree')) {
            $query->where('degree', $request->degree);
        }

        $quraans = $query->latest()->paginate(10);
        $classes = SchoolClass::all();

        // Calculate statistics
        $stats = [
            'total' => $query->count(),
            'good' => $query->where('status', 'good')->count(),
            'average' => $query->where('status', 'average')->count(),
            'weak' => $query->where('status', 'weak')->count(),
        ];

        // If it's an AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('admin.quraans.partials.quraan-table', compact('quraans', 'date', 'stats'));
        }

        return view('admin.quraans.index', compact('quraans', 'classes', 'date', 'stats'));
    }

    public function create()
    {
        $date = request('date', now()->format('Y-m-d'));
        $classId = request('class_id');

        // Get students who don't have quraan records for the selected date
        $query = Student::with(['classes', 'quraans' => function ($query) use ($date) {
            $query->whereDate('date', $date);
        }])
            ->whereDoesntHave('quraans', function ($query) use ($date) {
                $query->whereDate('date', $date);
            });

        if ($classId) {
            $query->whereHas('classes', function ($q) use ($classId) {
                $q->where('classes.id', $classId);
            });
        }

        $students = $query->get();
        $classes = SchoolClass::all();

        return view('admin.quraans.create', compact('students', 'classes', 'date'));
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
            $quraan = Quraan::where('student_id', $studentId)
                ->whereDate('date', $date)
                ->first();

            if ($quraan) {
                // Update existing record
                $updates[] = [
                    'id' => $quraan->id,
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
            Quraan::insert($records);
        }

        // Bulk update existing records
        if (!empty($updates)) {
            foreach ($updates as $update) {
                Quraan::where('id', $update['id'])->update([
                    'status' => $update['status'],
                    'degree' => $update['degree'],
                    'notes' => $update['notes'],
                    'updated_at' => $update['updated_at']
                ]);
            }
        }

        return redirect()
            ->route('admin.quraans.index')
            ->with('success', 'Quraan records have been saved successfully.');
    }

    public function show(Quraan $quraan)
    {
        return view('admin.quraans.show', compact('quraan'));
    }

    public function edit(Quraan $quraan)
    {
        return view('admin.quraans.edit', compact('quraan'));
    }

    public function update(Request $request, Quraan $quraan)
    {
        $request->validate([
            'status' => 'required|in:good,average,weak',
            'degree' => 'required|numeric|min:0|max:10',
            'notes' => 'nullable|string|max:255'
        ]);

        $quraan->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        // Recalculate degree based on new status
        $quraan->degree = $quraan->calculateDegree();
        $quraan->save();

        return redirect()->route('admin.quraans.index')
            ->with('success', 'Quraan record updated successfully.');
    }

    public function destroy(Quraan $quraan)
    {
        $quraan->delete();

        return redirect()
            ->route('admin.quraans.index')
            ->with('success', 'Quraan record has been deleted successfully.');
    }

    public function bulkCreate(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date'
        ]);

        $class = Classes::with('students')->findOrFail($request->class_id);
        $date = Carbon::parse($request->date);

        return view('admin.quraans.bulk-create', compact('class', 'date'));
    }
}
