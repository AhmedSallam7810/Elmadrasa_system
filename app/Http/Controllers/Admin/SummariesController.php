<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Summary;
use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use App\Imports\SummaryImport;
use App\Exports\SummaryTemplateExport;
use App\Models\SchoolClass;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class SummariesController extends Controller
{
    public function index(Request $request)
    {
        $query = Summary::with('student.classes')->orderBy('date', 'desc');
        $total_students = Student::with('classes')->count();
        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');

        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', fn($q) => $q->where('class_rooms.id', $request->class_id));
            $total_students = Student::with('classes')
                ->whereHas('classes', fn($q) => $q->where('class_rooms.id', $request->class_id))
                ->count();
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $summaries = $query->get();
        $stats = [
            'total_students' => $total_students,
            'total' => $summaries->count(),
            'good' => $summaries->where('status', 'good')->count(),
            'average' => $summaries->where('status', 'average')->count(),
            'weak' => $summaries->where('status', 'weak')->count(),
        ];

        if ($request->ajax()) {
            return view('admin.summaries.partials.summary-table', compact('summaries', 'stats', 'date'));
        }
        $classes = SchoolClass::all();
        return view('admin.summaries.index', compact('summaries', 'stats', 'classes', 'date'));
    }

    public function create()
    {
        $date = request('date', now()->format('Y-m-d'));
        $classId = request('class_id');
        $classes = SchoolClass::get();
        $students = Student::with('classes')
            ->whereDoesntHave('summaries', fn($q) => $q->whereDate('date', $date))
            ->when($classId, fn($q) => $q->whereHas('classes', fn($q2) => $q2->where('classes.id', $classId)))
            ->orderBy('name')->get();

        return view('admin.summaries.create', compact('students', 'classes', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status.*' => 'required|in:good,average,weak',
            'degree.*' => 'required|numeric|min:0|max:10',
            'notes.*' => 'nullable|string|max:255',
        ]);
        $date = $request->date;
        $new = [];
        $update = [];

        foreach ($request->student_ids as $id) {
            $rec = Summary::where('student_id', $id)->whereDate('date', $date)->first();
            if ($rec) {
                $update[] = [
                    'id' => $rec->id,
                    'status' => $request->status[$id],
                    'degree' => $request->degree[$id],
                    'notes' => $request->notes[$id] ?? null,
                    'updated_at' => now(),
                ];
            } else {
                $new[] = [
                    'student_id' => $id,
                    'date' => $date,
                    'status' => $request->status[$id],
                    'degree' => $request->degree[$id],
                    'notes' => $request->notes[$id] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        if ($new) Summary::insert($new);
        foreach ($update as $u) {
            Summary::where('id', $u['id'])->update(['status' => $u['status'], 'degree' => $u['degree'], 'notes' => $u['notes'], 'updated_at' => $u['updated_at']]);
        }

        return redirect()->route('admin.summaries.index')->with('success', __('admin.records_saved'));
    }

    public function show(Summary $summary)
    {
        return view('admin.summaries.show', compact('summary'));
    }

    public function edit(Summary $summary)
    {
        return view('admin.summaries.edit', compact('summary'));
    }

    public function update(Request $request, Summary $summary)
    {
        $request->validate([
            'status' => 'required|in:good,average,weak',
            'degree' => 'required|numeric|min:0|max:10',
            'notes' => 'nullable|string|max:255',
        ]);
        $summary->update($request->only('status', 'degree', 'notes'));
        return redirect()->route('admin.summaries.index')->with('success', __('admin.record_updated'));
    }

    public function destroy(Summary $summary)
    {
        $summary->delete();
        return redirect()->route('admin.summaries.index')->with('success', __('admin.record_deleted'));
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
            Excel::import(new SummaryImport($request->date), $request->file('excel_file'));

            return redirect()->route('admin.summaries.index')
                ->with('success', __('admin.summaries_imported_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('admin.error_importing_summaries') . ': ' . $e->getMessage());
        }
    }

    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        $query = Student::whereDoesntHave('summaries', function ($query) use ($request) {
            $query->whereDate('date', $request->date);
        });

        if ($request->class_id) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        $students = $query->get();
        $fileName = 'summary_template_' . $request->date . '.xlsx';

        return Excel::download(new SummaryTemplateExport($students), $fileName);
    }
}
