@extends('layouts.admin')

@section('title', 'Edit Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.edit_attendance') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.attendances.index') }}" class="small btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> {{ __('admin.back') }}
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
                                    <label for="date">{{ __('admin.date') }}</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date" value="{{ old('date', $attendance->date->format('Y-m-d')) }}" readonly>
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">{{ __('admin.student') }}</label>
                                    <input type="text" class="form-control" value="{{ $attendance->student->name }}" readonly>
                                    <input type="hidden" name="student_id" value="{{ $attendance->student_id }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">{{ __('admin.status') }}</label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                        <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>{{ __('admin.present') }}</option>
                                        <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>{{ __('admin.absent') }}</option>
                                        <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>{{ __('admin.late') }}</option>
                                        <option value="excused" {{ old('status', $attendance->status) == 'excused' ? 'selected' : '' }}>{{ __('admin.excused') }}</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="degree">{{ __('admin.degree') }} (0-30)</label>
                                    <input type="number" class="form-control @error('degree') is-invalid @enderror"
                                        id="degree" name="degree"
                                        value="{{ old('degree', $attendance->degree) }}"
                                        min="0" max="30" step="0.5" required>
                                    @error('degree')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="notes">{{ __('admin.notes') }}</label>
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
                            <button type="submit" class="btn btn-primary">{{ __('admin.update') }} {{ __('admin.attendance') }}</button>
                            <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
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
