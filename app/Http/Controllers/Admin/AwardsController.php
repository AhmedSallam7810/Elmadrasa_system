<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AwardsController extends Controller
{
    public function index()
    {
        $awards = Award::with('class')->paginate(10);
        return view('admin.awards.index', compact('awards'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        return view('admin.awards.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'required|integer|min:0',
            'class_id' => 'required|exists:classes,id',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Award::create($request->all());

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award created successfully.');
    }

    public function show(Award $award)
    {
        $award->load(['class', 'students']);
        return view('admin.awards.show', compact('award'));
    }

    public function edit(Award $award)
    {
        $classes = SchoolClass::all();
        return view('admin.awards.edit', compact('award', 'classes'));
    }

    public function update(Request $request, Award $award)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'required|integer|min:0',
            'class_id' => 'required|exists:classes,id',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $award->update($request->all());

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award updated successfully.');
    }

    public function destroy(Award $award)
    {
        $award->delete();

        return redirect()->route('admin.awards.index')
            ->with('success', 'Award deleted successfully.');
    }

    public function markAsCompleted(Award $award, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $award->students()->updateExistingPivot($request->student_id, [
            'is_completed' => true,
            'completed_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Award marked as completed.');
    }
}
