<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudyYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $studyYears = StudyYear::withCount(['terms', 'classes'])->latest()->get();
        return view('admin.study-years.index', compact('studyYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.study-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        StudyYear::create($validated);

        return redirect()
            ->route('admin.study-years.index')
            ->with('success', __('admin.study_year_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(StudyYear $studyYear)
    {
        $studyYear->load(['terms', 'classes.students']);
        return view('admin.study-years.show', compact('studyYear'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudyYear $studyYear)
    {
        return view('admin.study-years.edit', compact('studyYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudyYear $studyYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $studyYear->update($validated);

        return redirect()
            ->route('admin.study-years.index')
            ->with('success', __('admin.study_year_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudyYear $studyYear)
    {
        DB::transaction(function () use ($studyYear) {
            // Delete associated terms
            $studyYear->terms()->delete();

            // Delete the study year
            $studyYear->delete();
        });

        return redirect()
            ->route('admin.study-years.index')
            ->with('success', __('admin.study_year_deleted_successfully'));
    }
}
