@extends('layouts.admin')

@section('title', 'Edit Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Attendance</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.attendances.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attendances.update', $attendance->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">Student</label>
                                    <input type="text" class="form-control" value="{{ $attendance->student->full_name }}" readonly>
                                    <input type="hidden" name="student_id" value="{{ $attendance->student_id }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                        <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="excused" {{ old('status', $attendance->status) == 'excused' ? 'selected' : '' }}>Excused</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="degree">Degree</label>
                                    <input type="number" class="form-control @error('degree') is-invalid @enderror"
                                        id="degree" name="degree"
                                        value="{{ old('degree', $attendance->degree) }}"
                                        min="0" max="10" step="0.5" required>
                                    @error('degree')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <input type="text" class="form-control @error('notes') is-invalid @enderror"
                                        id="notes" name="notes"
                                        value="{{ old('notes', $attendance->notes) }}">
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Attendance</button>
                            <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle status change to update degree
    $('#status').change(function() {
        var degreeInput = $('#degree');

        if ($(this).val() === 'absent' || $(this).val() === 'excused') {
            degreeInput.val('0');
            degreeInput.prop('disabled', true);
        } else if ($(this).val() === 'present') {
            degreeInput.val('10');
            degreeInput.prop('disabled', false);
        } else if ($(this).val() === 'late') {
            degreeInput.val('5');
            degreeInput.prop('disabled', false);
        }
    });

    // Initialize degree value based on initial status
    $('#status').trigger('change');
});
</script>
@endpush
