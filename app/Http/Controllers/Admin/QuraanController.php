<?php

namespace App\Http\Controllers\Admin;

use App\Exports\QuraanTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\QuraanImport;
use App\Models\Quraan;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Muhafez;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class QuraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Quraan::with(['student', 'student.classes']);

        // Set default date to today if not provided
        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');

        $total_students = Student::with(['classes'])->count();
        // Apply date filter
        $query->whereDate('date', $date);

        // Apply class filter
        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
            $total_students = Student::with(['classes'])->whereHas('classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            })->count();
        }
        if ($request->filled('muhafez_id')) {
            $query->whereHas('student.muhafez', function ($q) use ($request) {
                $q->where('muhafezs.id', $request->muhafez_id);
            });
        }

        $total = $query->count();
        $good = $query->get()->where('status', 'good')->count();
        $average = $query->get()->where('status', 'average')->count();
        $weak = $query->get()->where('status', 'weak')->count();

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply degree filter
        if ($request->filled('degree')) {
            $query->where('degree', $request->degree);
        }

        $quraans = $query->latest()->get();
        $classes = SchoolClass::all();
        $muhafezs = Muhafez::all();

        // Calculate statistics
        $stats = [
            'total_students' => $total_students,
            'total' => $total,
            'good' => $good,
            'average' => $average,
            'weak' => $weak,
        ];

        // If it's an AJAX request, return only the table partial
        if ($request->ajax()) {
            return view('admin.quraans.partials.quraan-table', compact('quraans', 'date', 'stats'));
        }

        return view('admin.quraans.index', compact('quraans', 'classes', 'date', 'stats', 'muhafezs'));
    }

    public function create()
    {
        $date = request('date', now()->format('Y-m-d'));
        $classId = request('class_id');
        $muhafezId = request('muhafez_id');

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

        if ($muhafezId) {
            $query->whereHas('muhafez', function ($q) use ($muhafezId) {
                $q->where('muhafezs.id', $muhafezId);
            });
        }

        $students = $query->get();
        $classes = SchoolClass::all();
        $muhafezs = Muhafez::all();

        return view('admin.quraans.create', compact('students', 'classes', 'date', 'muhafezs'));
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
            'degree.*' => 'required|numeric|min:0|max:30',
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
            'degree' => 'required|numeric|min:0|max:30',
            'notes' => 'nullable|string|max:255'
        ]);

        $quraan->update([
            'status' => $request->status,
            'degree' => $request->degree,
            'notes' => $request->notes
        ]);

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
            'class_id' => 'required|exists:school_classes,id',
            'date' => 'required|date'
        ]);

        $class = SchoolClass::with('students')->findOrFail($request->class_id);
        $date = Carbon::parse($request->date);

        return view('admin.quraans.bulk-create', compact('class', 'date'));
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Excel::import(new QuraanImport($request->date), $request->file('excel_file'));

            return redirect()->route('admin.quraans.index')
                ->with('success', __('admin.quraan_imported_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('admin.error_importing_quraan') . ': ' . $e->getMessage());
        }
    }

    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $query = Student::whereDoesntHave('quraans', function ($query) use ($request) {
            $query->whereDate('date', $request->date);
        });

        if ($request->class_id) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        $students = $query->get();
        $fileName = 'quraan_template_' . $request->date . '.xlsx';

        return Excel::download(new QuraanTemplateExport($students), $fileName);
    }
}
