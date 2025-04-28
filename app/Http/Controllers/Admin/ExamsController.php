<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ExamsController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with('student.classes')->orderBy('date', 'desc');
        $total_students = Student::with('classes')->count();

        $date = $request->filled('date') ? $request->date : now()->format('Y-m-d');
        if ($request->filled('class_id')) {
            $query->whereHas('student.classes', fn($q) => $q->where('classes.id', $request->class_id));
            $total_students = Student::with('classes')
                ->whereHas('classes', fn($q) => $q->where('classes.id', $request->class_id))
                ->count();
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $exams = $query->get();
        $total = $exams->count();
        $good = $exams->where('status','good')->count();
        $average = $exams->where('status','average')->count();
        $weak = $exams->where('status','weak')->count();

        $stats = compact('total_students','total','good','average','weak');
        if ($request->ajax()) {
            return view('admin.exams.partials.exam-table', compact('exams','stats','date'));
        }
        $classes = SchoolClass::all();
        return view('admin.exams.index', compact('exams','stats','classes','date'));
    }

    public function create()
    {
        $date = request('date', now()->format('Y-m-d'));
        $classId = request('class_id');
        $classes = SchoolClass::orderBy('name')->get();
        $students = Student::with('classes')
            ->whereDoesntHave('exams', fn($q) => $q->whereDate('date', $date))
            ->when($classId, fn($q) => $q->whereHas('classes', fn($q2) => $q2->where('classes.id', $classId)))
            ->orderBy('name')->get();
        return view('admin.exams.create', compact('students','classes','date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'status' => 'required|array','status.*' => 'required|in:good,average,weak',
            'score' => 'required|array','score.*' => 'required|numeric|min:0|max:20',
            'notes' => 'nullable|array','notes.*' => 'nullable|string|max:255',
        ]);
        $date = $request->date;
        $new=[]; $update=[];
        foreach($request->student_ids as $id){
            $rec = Exam::where('student_id',$id)->whereDate('date',$date)->first();
            if($rec){
                $update[]=['id'=>$rec->id,'status'=>$request->status[$id],'score'=>$request->score[$id],'notes'=>$request->notes[$id]??null,'updated_at'=>now()];
            } else {
                $new[]=['student_id'=>$id,'date'=>$date,'status'=>$request->status[$id],'score'=>$request->score[$id],'notes'=>$request->notes[$id]??null,'created_at'=>now(),'updated_at'=>now()];
            }
        }
        if($new) Exam::insert($new);
        foreach($update as $u) Exam::where('id',$u['id'])->update(['status'=>$u['status'],'score'=>$u['score'],'notes'=>$u['notes'],'updated_at'=>$u['updated_at']]);
        return redirect()->route('admin.exams.index')->with('success','Exam records saved.');
    }

    public function show(Exam $exam)
    {
        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate(['status'=>'required|in:good,average,weak','score'=>'required|numeric|min:0|max:20','notes'=>'nullable|string|max:255']);
        $exam->update($request->only('status','score','notes'));
        return redirect()->route('admin.exams.index')->with('success','Exam record updated.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success','Exam record deleted.');
    }
}
