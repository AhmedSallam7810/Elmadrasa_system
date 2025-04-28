<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Muhafez;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MuhafezController extends Controller
{
    public function index()
    {
        $muhafezs = Muhafez::withCount('students')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.muhafez.index', compact('muhafezs'));
    }

    public function create()
    {
        return view('admin.muhafez.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Muhafez::create($request->all());

        return redirect()->route('admin.muhafezs.index')
            ->with('success', __('admin.muhafez_created_successfully'));
    }

    public function show($id)
    {
        $muhafez = Muhafez::findOrFail($id);
        return view('admin.muhafez.show', compact('muhafez'));
    }

    public function edit($id)
    {
        $muhafez = Muhafez::findOrFail($id);
        return view('admin.muhafez.edit', compact('muhafez'));
    }

    public function update(Request $request, $id)
    {
        $muhafez = Muhafez::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $muhafez->update($request->all());

        return redirect()->route('admin.muhafezs.index')
            ->with('success', __('admin.muhafez_updated_successfully'));
    }

    public function destroy($id)
    {
        $muhafez = Muhafez::findOrFail($id);
        $muhafez->delete();

        return redirect()->route('admin.muhafezs.index')
            ->with('success', __('admin.muhafez_deleted_successfully'));
    }

    /**
     * Show the form for enrolling students to a muhafez.
     */
    public function showEnrollStudents($id)
    {
        $muhafez = Muhafez::findOrFail($id);
        $enrolledStudents = Student::where('muhafez_id', $id)->get();

        // Get all students that are not assigned to any muhafez
        $availableStudents = Student::whereNull('muhafez_id')->get();

        return view('admin.muhafez.enroll-students', compact('muhafez', 'enrolledStudents', 'availableStudents'));
    }

    /**
     * Assign an existing student to muhafez.
     */
    public function enrollStudents(Request $request, $id)
    {
        $muhafez = Muhafez::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Assign student to muhafez
        Student::where('id', $request->student_id)->update(['muhafez_id' => $id]);

        return redirect()->route('admin.muhafezs.enroll-students', $id)
            ->with('success', __('admin.student_assigned_successfully'));
    }

    /**
     * Remove a student from muhafez.
     */
    public function removeStudent($muhafezId, $studentId)
    {
        $student = Student::where('id', $studentId)
            ->where('muhafez_id', $muhafezId)
            ->firstOrFail();

        $student->update(['muhafez_id' => null]);

        return redirect()->route('admin.muhafezs.enroll-students', $muhafezId)
            ->with('success', __('admin.student_removed_successfully'));
    }
}
