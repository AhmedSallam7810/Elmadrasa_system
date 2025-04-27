@extends('layouts.admin')

@section('title', 'Record Attendance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.record_attendance') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1 "></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="date">{{ __('admin.date') }}</label>
                                                <input type="date" class="form-control" id="date" value="{{ request('date', $date) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="class_id">{{ __('admin.class') }}</label>
                                                <select class="form-control select2" id="class_id">
                                                    <option value="">{{ __('admin.all_classes') }}</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}"
                                                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.attendances.store') }}" method="POST" id="attendance-form">
                        @csrf
                        <input type="hidden" name="date" id="form_date" value="{{ request('date', $date) }}">
                        <input type="hidden" name="class_id" id="form_class_id" value="{{ request('class_id') }}">

                        @if($students->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                No students found without attendance records for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}.
                                @if(request('class_id'))
                                    Try selecting a different class or date.
                                @else
                                    All students have attendance records for this date.
                                @endif
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="select-all">
                                                    <label class="custom-control-label" for="select-all"></label>
                                                </div>
                                            </th>
                                            <th>{{ __('admin.student_name') }}</th>
                                            <th>{{ __('admin.class') }}</th>
                                            <th>{{ __('admin.status') }}</th>
                                            <th>{{ __('admin.degree') }}</th>
                                            <th>{{ __('admin.notes') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input student-checkbox"
                                                            id="student_{{ $student->id }}"
                                                            name="student_ids[]"
                                                            value="{{ $student->id }}"
                                                            checked>
                                                        <label class="custom-control-label" for="student_{{ $student->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->classes->pluck('name')->implode(', ') }}</td>
                                                <td>
                                                    <select class="form-control status-select @error('status.'.$student->id) is-invalid @enderror"
                                                        name="status[{{ $student->id }}]"
                                                        data-student-id="{{ $student->id }}">
                                                        <option value="present">{{ __('admin.present') }}</option>
                                                        <option value="absent">{{ __('admin.absent') }}</option>
                                                        <option value="late">{{ __('admin.late') }}</option>
                                                    </select>
                                                    @error('status.'.$student->id)
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control degree-input @error('degree.'.$student->id) is-invalid @enderror"
                                                        name="degree[{{ $student->id }}]"
                                                        value="30"
                                                        min="0"
                                                        max="30"
                                                        step="0.5"
                                                        data-student-id="{{ $student->id }}">
                                                    @error('degree.'.$student->id)
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control @error('notes.'.$student->id) is-invalid @enderror"
                                                        name="notes[{{ $student->id }}]"
                                                        value="{{ old('notes.'.$student->id) }}">
                                                    @error('notes.'.$student->id)
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                                <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                            </div>
                        @endif
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
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Handle select all checkbox
    $('#select-all').change(function() {
        $('.student-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Update select all checkbox state
    $('.student-checkbox').change(function() {
        var allChecked = $('.student-checkbox:checked').length === $('.student-checkbox').length;
        $('#select-all').prop('checked', allChecked);
    });

    // Handle status change to update degree to 30-point scale
    $('.status-select').change(function() {
        var studentId = $(this).data('student-id');
        var degreeInput = $('.degree-input[data-student-id="' + studentId + '"]');
        if ($(this).val() === 'absent') {
            degreeInput.val('0').prop('disabled', true);
        } else if ($(this).val() === 'present') {
            degreeInput.val('30').prop('disabled', false);
        } else if ($(this).val() === 'late') {
            degreeInput.val('15').prop('disabled', false);
        }
    });

    // Initialize degree values based on initial status
    $('.status-select').each(function() {
        $(this).trigger('change');
    });

    // Filter students by class
    $('#class_id').change(function() {
        var classId = $(this).val();
        var date = $('#date').val();
        $('#form_class_id').val(classId);
        window.location.href = '{{ route('admin.attendances.create') }}?class_id=' + classId + '&date=' + date;
    });

    // Update date and reload
    $('#date').change(function() {
        var classId = $('#class_id').val();
        var date = $(this).val();
        $('#form_date').val(date);
        window.location.href = '{{ route('admin.attendances.create') }}?class_id=' + classId + '&date=' + date;
    });
});
</script>
@endpush
