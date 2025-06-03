@extends('layouts.admin')

@section('title', __('admin.record_quraan'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-2"></i>{{ __('admin.record_quraan') }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.quraans.index') }}" class="btn btn-secondary btn-sm">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date">{{ __('admin.date') }}</label>
                                                <input type="date" class="form-control filter-input" id="date" value="{{ request('date', $date) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="class_id">{{ __('admin.class') }}</label>
                                                <select class="form-control select2 filter-input" id="class_id">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="muhafez_id">{{ __('admin.muhafez') }}</label>
                                                <select class="form-control select2 filter-input" id="muhafez_id" name="muhafez_id">
                                                    <option value="">{{ __('admin.all_muhafezs') }}</option>
                                                    @foreach($muhafezs as $muhafez)
                                                        <option value="{{ $muhafez->id }}" {{ request('muhafez_id') == $muhafez->id ? 'selected' : '' }}>{{ $muhafez->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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

                    <form action="{{ route('admin.quraans.store') }}" method="POST" id="quraan-form">
                        @csrf
                        <input type="hidden" name="date" id="form_date" value="{{ request('date', $date) }}">
                        <input type="hidden" name="class_id" id="form_class_id" value="{{ request('class_id') }}">
                        <input type="hidden" name="muhafez_id" id="form_muhafez_id" value="{{ request('muhafez_id') }}">

                        @if($students->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                {{ __('admin.no_students_found_without_records') }}
                            </div>
                        @else
                            <div class="table-responsive" id="students-table">
                                @include('admin.quraans.partials.create-students-table')
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                                <a href="{{ route('admin.quraans.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
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
                <h5 class="modal-title" id="uploadExcelModalLabel">{{ __('admin.upload_quraan_excel') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.quraans.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="date" id="upload_date">
                    <div class="form-group">
                        <label for="excel_file">{{ __('admin.excel_file') }}</label>
                        <input type="file" class="form-control-file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                        <small class="form-text text-muted">{{ __('admin.quraan_excel_format_help') }}</small>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            degreeInput.val('10');
        } else if ($(this).val() === 'good') {
            degreeInput.val('30');
        } else if ($(this).val() === 'average') {
            degreeInput.val('20');
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
        var muhafez = $('#muhafez_id').val();
        $('#form_class_id').val(classId);
        window.location.href = '{{ route('admin.quraans.create') }}?class_id=' + classId + '&date=' + date + '&muhafez_id=' + muhafez;
    });

    // Update date and reload
    $('#date').change(function() {
        var classId = $('#class_id').val();
        var date = $(this).val();
        var muhafez = $('#muhafez_id').val();
        $('#form_date').val(date);
        window.location.href = '{{ route('admin.quraans.create') }}?class_id=' + classId + '&date=' + date + '&muhafez_id=' + muhafez;
    });

    // Update muhafez filter
    $('#muhafez_id').change(function() {
        var classId = $('#class_id').val();
        var date = $('#date').val();
        var muhafez = $(this).val();
        $('#form_muhafez_id').val(muhafez);
        window.location.href = '{{ route('admin.quraans.create') }}?class_id=' + classId + '&date=' + date + '&muhafez_id=' + muhafez;
    });

    // Export Excel template
    $('#export-excel').click(function() {
        const classId = $('#class_id').val();
        const date = $('#date').val();
        const muhafezId = $('#muhafez_id').val();

        if (!date) {
            toastr.error('{{ __("admin.please_select_date") }}');
            return;
        }

        window.location.href = `{{ route('admin.quraans.download-template') }}?class_id=${classId}&date=${date}&muhafez_id=${muhafezId}`;
    });

    // Handle upload modal
    $('#uploadExcelModal').on('show.bs.modal', function () {
        const date = $('#date').val();
        const classId = $('#class_id').val();
        const muhafezId = $('#muhafez_id').val();

        if (!date) {
            toastr.error('{{ __("admin.please_select_date") }}');
            return false;
        }

        $('#upload_date').val(date);
    });
});
</script>
