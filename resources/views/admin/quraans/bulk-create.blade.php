@extends('layouts.admin')

@section('title', 'Bulk Record Quraan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Record Quraan for {{ $class->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.quraans.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quraans.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <input type="hidden" name="date" value="{{ $date->format('Y-m-d') }}">

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Class</label>
                                    <input type="text" class="form-control" value="{{ $class->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="text" class="form-control" value="{{ $date->format('Y-m-d') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($class->students as $student)
                                        <tr>
                                            <td>
                                                {{ $student->full_name }}
                                                <input type="hidden" name="attendances[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                            </td>
                                            <td>
                                                <select class="form-control" name="attendances[{{ $loop->index }}][status]" required>
                                                    <option value="present">Present</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="late">Late</option>
                                                    <option value="excused">Excused</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="quraans[{{ $loop->index }}][notes]" placeholder="Optional notes">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save Quraan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
