@extends('layouts.admin')

@section('title', __('admin.add_research'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.add_research') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.researchs.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.researchs.store') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date">{{ __('admin.date') }}</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', request('date', now()->format('Y-m-d'))) }}" required>
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_id">{{ __('admin.class') }}</label>
                                    <select class="form-control @error('class_id') is-invalid @enderror" id="class_id" name="class_id">
                                        <option value="">{{ __('admin.select_class') }}</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id', request('class_id')) == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
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

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all" checked>
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
                                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox" checked>
                                            </td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->classes->first()?->name }}</td>
                                            <td>
                                                <select class="form-control status-select" name="status[{{ $student->id }}]" data-student-id="{{ $student->id }}" required>
                                                    <option value="good" selected>{{ __('admin.good') }}</option>
                                                    <option value="average">{{ __('admin.average') }}</option>
                                                    <option value="weak">{{ __('admin.weak') }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control degree-input" name="degree[{{ $student->id }}]" value="10" min="0" max="10" step="0.5" data-student-id="{{ $student->id }}" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="notes[{{ $student->id }}]" value="{{ old("notes.{$student->id}") }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                            <a href="{{ route('admin.researchs.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                        </div>
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
                <h5 class="modal-title" id="uploadExcelModalLabel">{{ __('admin.upload_researchs_excel') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.researchs.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="date" id="upload_date">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excel_file">{{ __('admin.excel_file') }}</label>
                        <input type="file" class="form-control-file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                        <small class="form-text text-muted">{{ __('admin.researchs_excel_format_help') }}</small>
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
$(function(){
    // Handle class filter
    $('#class_id').change(function() {
        const date = $('#date').val();
        const classId = $(this).val();
        window.location.href = `{{ route('admin.researchs.create') }}?date=${date}&class_id=${classId}`;
    });

    // Handle date filter
    $('#date').change(function() {
        const date = $(this).val();
        const classId = $('#class_id').val();
        window.location.href = `{{ route('admin.researchs.create') }}?date=${date}&class_id=${classId}`;
    });

    // Export Excel template
    $('#export-excel').click(function() {
        const classId = $('#class_id').val();
        const date = $('#date').val();

        if (!date) {
            toastr.error('{{ __("admin.please_select_date") }}');
            return;
        }

        window.location.href = `{{ route('admin.researchs.download-template') }}?class_id=${classId}&date=${date}`;
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

    // Handle select all checkbox
    $('#select-all').change(function() {
        $('.student-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Handle status change
    $('.status-select').change(function() {
        const studentId = $(this).data('student-id');
        const status = $(this).val();
        const degreeInput = $(`.degree-input[data-student-id="${studentId}"]`);

        if (status === 'weak') {
            degreeInput.val(5);
        } else if (status === 'average') {
            degreeInput.val(7);
        } else {
            degreeInput.val(10);
        }
    });
});
</script>
