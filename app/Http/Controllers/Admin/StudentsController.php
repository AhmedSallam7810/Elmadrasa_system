<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Muhafez;
use App\Imports\StudentsImport;
use App\Imports\StudentImport;
use App\Exports\StudentTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class StudentsController extends Controller
{
    /**
     * Display a listing of the students.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by class
        if ($request->filled('class')) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class);
            });
        }

        // Filter by muhafez
        if ($request->filled('muhafez')) {
            $query->where('muhafez_id', $request->muhafez);
        }

        $students = $query->with(['classes', 'muhafez'])
            ->orderBy('created_at', 'desc')
            ->get();

        $classes = SchoolClass::all();
        $muhafezs = Muhafez::where('status', 'active')->get();

        if ($request->ajax()) {
            return view('admin.students.partials.students-table', compact('students'));
        }

        return view('admin.students.index', compact('students', 'classes', 'muhafezs'));
    }

    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes = SchoolClass::all();
        $muhafezs = Muhafez::where('status', 'active')->get();
        return view('admin.students.create', compact('classes', 'muhafezs'));
    }

    /**
     * Store a newly created student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'muhafez_id' => ['nullable', 'exists:muhafezs,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student = Student::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'muhafez_id' => $request->muhafez_id,
        ]);

        // Assign class to the student if selected
        if ($request->filled('class_id')) {
            $student->classes()->attach($request->class_id, [
                'enrolled_at' => now(),
                'is_active' => true
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', __('admin.student_created_successfully'));
    }

    /**
     * Display the specified student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::with(['classes', 'muhafez', 'quraans', 'werds', 'attendances', 'behaviors', 'summaries', 'researchs'])->findOrFail($id);

        // Aggregate performance data by date
        $student->load(['quraans', 'werds', 'attendances', 'behaviors', 'summaries', 'researchs']);
        // Build maps: date string => total degree
        $quraanMap = $student->quraans
            ->groupBy(fn($r) => $r->date->toDateString())
            ->map(fn($group) => $group->sum('degree'))
            ->toArray();
        $werdMap = $student->werds
            ->groupBy(fn($r) => $r->date->toDateString())
            ->map(fn($group) => $group->sum('degree'))
            ->toArray();
        $attendanceMap = $student->attendances
            ->groupBy(fn($a) => $a->date->toDateString())
            ->map(fn($group) => $group->sum('degree'))
            ->toArray();
        $behaviorMap = $student->behaviors
            ->groupBy(fn($b) => $b->date->toDateString())
            ->map(fn($group) => $group->sum('degree'))
            ->toArray();

        $summariesMap = $student->summaries
            ->groupBy(fn($s) => $s->date->toDateString())
            ->map(fn($group) => $group->sum('degree'))
            ->toArray();

        $researchsMap = $student->researchs
            ->groupBy(fn($r) => $r->date->toDateString())
            ->map(fn($group) => $group->sum('degree'))
            ->toArray();
        // Collect all unique dates and sort
        $dates = collect(
            array_unique(
                array_merge(
                    array_keys($quraanMap),
                    array_keys($werdMap),
                    array_keys($attendanceMap),
                    array_keys($behaviorMap),
                    array_keys($summariesMap),
                    array_keys($researchsMap),
                )
            )
        )->sort()->values();
        // Map dates to degree arrays, defaulting to zero
        $quraanDegrees = $dates->map(fn($d) => $quraanMap[$d] ?? 0);
        $werdDegrees = $dates->map(fn($d) => $werdMap[$d] ?? 0);
        $attendanceDegrees = $dates->map(fn($d) => $attendanceMap[$d] ?? 0);
        $behaviorDegrees = $dates->map(fn($d) => $behaviorMap[$d] ?? 0);
        $summariesDegrees = $dates->map(fn($d) => $summariesMap[$d] ?? 0);
        $researchsDegrees = $dates->map(fn($d) => $researchsMap[$d] ?? 0);

        return view('admin.students.show', compact('student', 'dates', 'quraanDegrees', 'werdDegrees', 'attendanceDegrees', 'behaviorDegrees', 'summariesDegrees', 'researchsDegrees'));
    }

    /**
     * Show the form for editing the specified student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Student::with(['classes', 'muhafez'])->findOrFail($id);
        $classes = SchoolClass::all();
        $muhafezs = Muhafez::where('status', 'active')->get();
        return view('admin.students.edit', compact('student', 'classes', 'muhafezs'));
    }

    /**
     * Update the specified student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'muhafez_id' => ['nullable', 'exists:muhafezs,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'muhafez_id' => $request->muhafez_id,
        ]);

        // Update class
        $student->classes()->detach(); // Remove all existing classes
        if ($request->filled('class_id')) {
            $student->classes()->attach($request->class_id, [
                'enrolled_at' => now(),
                'is_active' => true
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('success', __('admin.student_updated_successfully'));
    }

    /**
     * Remove the specified student from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', __('admin.student_deleted_successfully'));
    }

    /**
     * Import students from Excel file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Excel::import(new StudentImport, $request->file('excel_file'));

            return redirect()->route('admin.students.index')
                ->with('success', __('admin.students_imported_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('admin.error_importing_students') . ': ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $fileName = 'student_template.xlsx';
        return Excel::download(new StudentTemplateExport, $fileName);
    }
}
