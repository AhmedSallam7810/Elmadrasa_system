<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Behavior;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class BehaviorsController extends Controller
{
    public function index(Request $request)
    {
        $query = Behavior::with(['student.classes'])->orderBy('date', 'desc');
        $total_students = Student::with(['classes'])->count();

        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');
        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', fn($q) => $q->where('classes.id', $request->class_id));
            $total_students = Student::with(['classes'])
                ->whereHas('classes', fn($q) => $q->where('classes.id', $request->class_id))
                ->count();
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $total = $query->count();
        $behaviors = $query->get();
        $good = $behaviors->where('status', 'good')->count();
        $average = $behaviors->where('status', 'average')->count();
        $weak = $behaviors->where('status', 'weak')->count();

        $stats = compact('total_students', 'total', 'good', 'average', 'weak');
        if ($request->ajax()) {
            return view('admin.behaviors.partials.behavior-table', compact('behaviors', 'stats', 'date'));
        }
        $classes = SchoolClass::all();
        return view('admin.behaviors.index', compact('behaviors', 'stats', 'classes', 'date'));
    }

    public function create()
    {
        $date = request('date', now()->format('Y-m-d'));
        $classId = request('class_id');
        $classes = SchoolClass::orderBy('name')->get();
        $studentsQuery = Student::with('classes')
            ->whereDoesntHave('behaviors', fn($q) => $q->whereDate('date', $date));
        if ($classId) {
            $studentsQuery->whereHas('classes', fn($q) => $q->where('school_classes.id', $classId));
        }
        $students = $studentsQuery->orderBy('name')->get();
        return view('admin.behaviors.create', compact('students', 'classes', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array','status.*' => 'required|in:good,average,weak',
            'degree' => 'required|array','degree.*' => 'required|numeric|min:0|max:10',
            'notes' => 'nullable|array','notes.*' => 'nullable|string|max:255',
        ]);
        $date = $request->date;
        $records = [];$updates = [];
        foreach ($request->student_ids as $id) {
            $rec = Behavior::where('student_id', $id)->whereDate('date', $date)->first();
            if ($rec) {
                $updates[] = ['id'=>$rec->id,'status'=>$request->status[$id],'degree'=>$request->degree[$id],'notes'=>$request->notes[$id]??null,'updated_at'=>now()];
            } else {
                $records[] = ['student_id'=>$id,'date'=>$date,'status'=>$request->status[$id],'degree'=>$request->degree[$id],'notes'=>$request->notes[$id]??null,'created_at'=>now(),'updated_at'=>now()];
            }
        }
        if ($records) Behavior::insert($records);
        foreach ($updates as $u) Behavior::where('id',$u['id'])->update(['status'=>$u['status'],'degree'=>$u['degree'],'notes'=>$u['notes'],'updated_at'=>$u['updated_at']]);
        return redirect()->route('admin.behaviors.index')->with('success', 'Behavior records saved successfully.');
    }

    public function show(Behavior $behavior)
    {
        return view('admin.behaviors.show', compact('behavior'));
    }

    public function edit(Behavior $behavior)
    {
        return view('admin.behaviors.edit', compact('behavior'));
    }

    public function update(Request $request, Behavior $behavior)
    {
        $request->validate(['status'=>'required|in:good,average,weak','degree'=>'required|numeric|min:0|max:10','notes'=>'nullable|string|max:255']);
        $behavior->update($request->only('status','degree','notes'));
        return redirect()->route('admin.behaviors.index')->with('success', 'Behavior record updated successfully.');
    }

    public function destroy(Behavior $behavior)
    {
        $behavior->delete();
        return redirect()->route('admin.behaviors.index')->with('success', 'Behavior record deleted successfully.');
    }
}
