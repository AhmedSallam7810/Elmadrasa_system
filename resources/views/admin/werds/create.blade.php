@extends('layouts.admin')

@section('title', 'Record Werd')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.record_werd') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.werds.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="date">{{ __('admin.date') }}</label>
                                                <input type="date" class="form-control" id="date" value="{{ request('date', $date) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <div class="d-flex">
                                                    <button type="button" class="btn btn-success mr-2" id="export-excel">
                                                        <i class="fas fa-file-excel mr-1"></i> {{ __('admin.export_excel') }}
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadExcelModal">
                                                        <i class="fas fa-upload mr-1"></i> {{ __('admin.upload_excel') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.werds.store') }}" method="POST" id="werd-form">
                        @csrf
                        <input type="hidden" name="date" id="form_date" value="{{ request('date', $date) }}">
                        <input type="hidden" name="class_id" id="form_class_id" value="{{ request('class_id') }}">

                        @if($students->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ __('admin.no_students_found_without_records') }}
                                {{-- @if(request('class_id'))
                                    Try selecting a different class or date.
                                @else
                                    All students have werd records for this date.
                                @endif --}}
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
                                                        <option value="good">{{ __('admin.good') }}</option>
                                                        <option value="average">{{ __('admin.average') }}</option>
                                                        <option value="weak">{{ __('admin.weak') }}</option>
                                                    </select>
                                                    @error('status.'.$student->id)
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control degree-input @error('degree.'.$student->id) is-invalid @enderror"
                                                        name="degree[{{ $student->id }}]"
                                                        data-student-id="{{ $student->id }}"
                                                        min="0" max="10" step="0.5" required>
                                                    @error('degree.'.$student->id)
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control @error('notes.'.$student->id) is-invalid @enderror"
                                                        name="notes[{{ $student->id }}]"
                                                        placeholder="{{ __('admin.optional_notes') }}">
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
                                <a href="{{ route('admin.werds.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Excel Upload Modal -->
<div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadExcelModalLabel">{{ __('admin.upload_werd_excel') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.werds.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="date" id="upload_date">
                    <div class="form-group">
                        <label for="excel_file">{{ __('admin.excel_file') }}</label>
                        <input type="file" class="form-control-file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                        <small class="form-text text-muted">{{ __('admin.werd_excel_format_help') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('admin.upload') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

    // Handle status change to update degree
    $('.status-select').change(function() {
        var studentId = $(this).data('student-id');
        var degreeInput = $('.degree-input[data-student-id="' + studentId + '"]');

        if ($(this).val() === 'weak') {
            degreeInput.val('3');
        } else if ($(this).val() === 'good') {
            degreeInput.val('10');
        } else if ($(this).val() === 'average') {
            degreeInput.val('7');
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
        window.location.href = '{{ route('admin.werds.create') }}?class_id=' + classId + '&date=' + date;
    });

    // Update date and reload
    $('#date').change(function() {
        var classId = $('#class_id').val();
        var date = $(this).val();
        $('#form_date').val(date);
        window.location.href = '{{ route('admin.werds.create') }}?class_id=' + classId + '&date=' + date;
    });

    // Export Excel template
    $('#export-excel').click(function() {
        const classId = $('#class_id').val();
        const date = $('#date').val();

        if (!date) {
            toastr.error('{{ __("admin.please_select_date") }}');
            return;
        }

        window.location.href = `{{ route('admin.werds.download-template') }}?class_id=${classId}&date=${date}`;
    });

    // Handle upload modal
    $('#uploadExcelModal').on('show.bs.modal', function () {
        const date = $('#date').val();
        const classId = $('#class_id').val();

        if (!date) {
            toastr.error('{{ __("admin.please_select_date") }}');
            return false;
        }

        $('#upload_date').val(date);
    });
});
</script>
