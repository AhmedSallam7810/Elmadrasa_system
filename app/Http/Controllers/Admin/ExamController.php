<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('classRoom')->latest()->get();
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $classes = ClassRoom::where('is_active', true)->get();
        return view('admin.exams.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_score' => 'required|numeric|min:0',
            'passing_score' => 'required|numeric|min:0|lte:total_score',
            'exam_date' => 'required|date',
            'class_id' => 'required|exists:class_rooms,id',
            'is_active' => 'boolean'
        ]);

        Exam::create($validated);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        $exam->load(['classRoom', 'results.student']);
        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $classes = ClassRoom::where('is_active', true)->get();
        return view('admin.exams.edit', compact('exam', 'classes'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_score' => 'required|numeric|min:0',
            'passing_score' => 'required|numeric|min:0|lte:total_score',
            'exam_date' => 'required|date',
            'class_id' => 'required|exists:class_rooms,id',
            'is_active' => 'boolean'
        ]);

        $exam->update($validated);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam deleted successfully.');
    }
}
