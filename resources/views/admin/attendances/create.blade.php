@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.create_attendance') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.attendances.index') }}" class="small btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attendances.store') }}" method="POST" id="attendance-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class_id">{{ __('admin.class') }}</label>
                                    <select class="form-control @error('class_id') is-invalid @enderror" id="class_id" name="class_id" >
                                        <option value="">{{ __('admin.select_class') }}</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                                    <label for="date">{{ __('admin.date') }}</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
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



                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('admin.students') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div id="students-container">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                {{ __('admin.please_select_class_and_date') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                                    <i class="fas fa-save mr-1"></i> {{ __('admin.save') }}
                                </button>
                                <a href="{{ route('admin.attendances.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-1"></i> {{ __('admin.cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                  <!-- Excel Upload Modal -->
                  <div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="uploadExcelModalLabel">{{ __('admin.upload_attendance_excel') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.attendances.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <input type="hidden" name="date" id="upload_date">
                                    <div class="form-group">
                                        <label for="excel_file">{{ __('admin.excel_file') }}</label>
                                        <input type="file" class="form-control-file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                                        <small class="form-text text-muted">{{ __('admin.attendance_excel_format_help') }}</small>
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
            </div>
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
    let selectedStudents = new Set();
    let loadingTimeout;

    // Function to load students
    function loadStudents() {
        const classId = $('#class_id').val();
        const date = $('#date').val();
        console.log('Loading students with:', { classId, date });

        if (!date) {
            $('#students-container').html('<div class="alert alert-info"><i class="fas fa-info-circle mr-2"></i>{{ __("admin.please_select_date") }}</div>');
            return;
        }

        $('#students-container').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">{{ __("admin.loading_students") }}</p></div>');

        $.get(`{{ route('admin.attendances.get-students-without-attendance') }}`, {
            class_id: classId,
            date: date
        })
        .done(function(response) {
            console.log('Server response:', response);
            if (response.html) {
                $('#students-container').html(response.html);
                initializeCheckboxes();
            } else {
                console.error('Invalid response format:', response);
                $('#students-container').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-2"></i>{{ __("admin.error_loading_students") }}</div>');
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            console.error('AJAX error:', { status: textStatus, error: errorThrown, response: jqXHR.responseText });
            $('#students-container').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-2"></i>{{ __("admin.error_loading_students") }}</div>');
        });
    }
    loadStudents();

    // Load students when class or date changes
    $('#class_id, #date').on('change', function() {
        // Clear any existing timeout
        if (loadingTimeout) {
            clearTimeout(loadingTimeout);
        }

        // Set a new timeout to load students after 500ms
        loadingTimeout = setTimeout(loadStudents, 500);
    });

    // Export Excel template
    $('#export-excel').click(function() {
        const classId = $('#class_id').val();
        const date = $('#date').val();

        if (!date) {
            toastr.error('{{ __("admin.please_select_date") }}');
            return;
        }

        window.location.href = `{{ route('admin.attendances.download-template') }}?class_id=${classId}&date=${date}`;
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
        $('#upload_class_id').val(classId);
    });

    // Initialize checkboxes and form validation
    function initializeCheckboxes() {
        // Select all checkbox
        $('#select-all').change(function() {
            $('.student-checkbox').prop('checked', $(this).prop('checked'));
            updateSelectedStudents();
        });

        // Individual student checkboxes
        $('.student-checkbox').change(function() {
            updateSelectedStudents();
        });

        // Status change
        $('.status-select').change(function() {
            const studentId = $(this).data('student-id');
            const status = $(this).val();
            const degreeInput = $(`.degree-input[data-student-id="${studentId}"]`);

            if (status === 'absent' || status === 'excused') {
                degreeInput.val('0').prop('disabled', true);
            } else {
                degreeInput.val('30').prop('disabled', false);
            }
        });

        // Initial status check
        $('.status-select').each(function() {
            $(this).trigger('change');
        });
    }

    // Update selected students set and form validation
    function updateSelectedStudents() {
        selectedStudents.clear();
        $('.student-checkbox:checked').each(function() {
            selectedStudents.add($(this).val());
        });

        $('#submit-btn').prop('disabled', selectedStudents.size === 0);
    }

    // Form submission validation
    $('#attendance-form').submit(function(e) {
        if (selectedStudents.size === 0) {
            e.preventDefault();
            toastr.error('{{ __("admin.please_select_at_least_one_student") }}');
        }
    });

    // Load students on page load if class and date are selected
    if ($('#class_id').val() && $('#date').val()) {
        loadStudents();
    }
});
</script>
