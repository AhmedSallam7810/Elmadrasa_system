<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassStudent;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        SchoolClass::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', __('admin.class_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $class = SchoolClass::with('students')->findOrFail($id);
        return view('admin.classes.show', compact('class'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $class = SchoolClass::findOrFail($id);
        return view('admin.classes.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $class = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', __('admin.class_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', __('admin.class_deleted_successfully'));
    }

    /**
     * Show the form for enrolling students in the class.
     */
    public function enrollStudents(string $id)
    {
        $class = SchoolClass::findOrFail($id);
        $enrolled_students = ClassStudent::pluck('student_id');
        $students = Student::whereNotIn('id', $enrolled_students)->get();

        return view('admin.classes.enroll-students', compact('class', 'students'));
    }

    /**
     * Store the enrolled students in the class.
     */
    public function storeEnrolledStudents(Request $request, string $id)
    {
        $class = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        foreach ($validated['student_ids'] as $studentId) {
            $class->students()->attach($studentId, [
                'enrolled_at' => now(),
                'is_active' => true
            ]);
        }

        return redirect()->route('admin.classes.show', $class->id)
            ->with('success', __('admin.students_enrolled_successfully'));
    }

    /**
     * Remove a student from the class.
     */
    public function removeStudent(string $classId, string $studentId)
    {
        $class = SchoolClass::findOrFail($classId);
        $class->students()->detach($studentId);

        return redirect()->route('admin.classes.show', $class->id)
            ->with('success', __('admin.student_removed_successfully'));
    }
}
