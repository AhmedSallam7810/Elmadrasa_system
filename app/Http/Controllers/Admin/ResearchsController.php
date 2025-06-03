<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Research;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ResearchTemplateExport;
use App\Imports\ResearchImport;
use Illuminate\Support\Facades\Validator;

class ResearchsController extends Controller
{
    public function index(Request $request)
    {
        $query = Research::with(['student.classes'])->orderBy('date', 'desc');
        $total_students = Student::with('classes')->count();
        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');
        $query->whereDate('date', $date);
        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', fn($q) => $q->where('classes.id', $request->class_id));
            $total_students = Student::with('classes')
                ->whereHas('classes', fn($q) => $q->where('classes.id', $request->class_id))
                ->count();
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $researchs = $query->get();
        $total = $researchs->count();
        $good = $researchs->where('status', 'good')->count();
        $average = $researchs->where('status', 'average')->count();
        $weak = $researchs->where('status', 'weak')->count();

        $stats = compact('total_students', 'total', 'good', 'average', 'weak');
        if ($request->ajax()) {
            return view('admin.researchs.partials.research-table', compact('researchs', 'stats', 'date'));
        }
        $classes = SchoolClass::all();
        return view('admin.researchs.index', compact('researchs', 'stats', 'classes', 'date'));
    }

    public function create()
    {
        $date = request('date', now()->format('Y-m-d'));
        $classId = request('class_id');
        $classes = SchoolClass::orderBy('name')->get();
        $students = Student::with('classes')
            ->whereDoesntHave('researchs', fn($q) => $q->whereDate('date', $date))
            ->when($classId, fn($q) => $q->whereHas('classes', fn($q2) => $q2->where('classes.id', $classId)))
            ->orderBy('name')->get();
        return view('admin.researchs.create', compact('students', 'classes', 'date'));
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
            'notes.*' => 'nullable|string|max:255',
        ]);
        $date = $request->date;
        $new = [];
        $update = [];
        foreach ($request->student_ids as $id) {
            $rec = Research::where('student_id', $id)->whereDate('date', $date)->first();
            if ($rec) {
                $update[] = ['id' => $rec->id, 'status' => $request->status[$id], 'degree' => $request->degree[$id], 'notes' => $request->notes[$id] ?? null, 'updated_at' => now()];
            } else {
                $new[] = ['student_id' => $id, 'date' => $date, 'status' => $request->status[$id], 'degree' => $request->degree[$id], 'notes' => $request->notes[$id] ?? null, 'created_at' => now(), 'updated_at' => now()];
            }
        }
        if ($new) Research::insert($new);
        foreach ($update as $u) Research::where('id', $u['id'])->update(['status' => $u['status'], 'degree' => $u['degree'], 'notes' => $u['notes'], 'updated_at' => $u['updated_at']]);
        return redirect()->route('admin.researchs.index')->with('success', 'Research records saved.');
    }

    public function show(Research $research)
    {
        return view('admin.researchs.show', compact('research'));
    }

    public function edit(Research $research)
    {
        return view('admin.researchs.edit', compact('research'));
    }

    public function update(Request $request, Research $research)
    {
        $request->validate(['status' => 'required|in:good,average,weak', 'degree' => 'required|numeric|min:0|max:10', 'notes' => 'nullable|string|max:255']);
        $research->update($request->only('status', 'degree', 'notes'));
        return redirect()->route('admin.researchs.index')->with('success', 'Research record updated.');
    }

    public function destroy(Research $research)
    {
        $research->delete();
        return redirect()->route('admin.researchs.index')->with('success', 'Research record deleted.');
    }

    public function downloadTemplate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'class_id' => 'nullable|exists:class_rooms,id',
        ]);

        $query = Student::whereDoesntHave('researchs', function ($query) use ($request) {
            $query->whereDate('date', $request->date);
        });

        if ($request->class_id) {
            $query->whereHas('classes', function ($q) use ($request) {
                $q->where('class_rooms.id', $request->class_id);
            });
        }

        $students = $query->get();
        $fileName = 'research_template_' . $request->date . '.xlsx';

        return Excel::download(new ResearchTemplateExport($students), $fileName);
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
            Excel::import(new ResearchImport($request->date), $request->file('excel_file'));

            return redirect()->route('admin.researchs.index')
                ->with('success', __('admin.researchs_imported_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('admin.error_importing_researchs') . ': ' . $e->getMessage());
        }
    }
}
