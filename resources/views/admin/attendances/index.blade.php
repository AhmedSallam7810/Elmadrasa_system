@extends('layouts.admin')

@section('title', 'Attendance Records')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check mr-2"></i>{{ __('admin.attendances') }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.attendances.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> {{ __('admin.record_attendance') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date_filter">{{ __('admin.filter_by_date') }}</label>
                                                <div class="input-group date" id="datepicker">
                                                    <input type="date" class="form-control" id="date_filter" value="{{ $date }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="class_filter">{{ __('admin.filter_by_class') }}</label>
                                                <select class="form-control select2" id="class_filter">
                                                    <option value="">{{ __('admin.all_classes') }}</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status_filter">{{ __('admin.filter_by_status') }}</label>
                                                <select class="form-control select2" id="status_filter">
                                                    <option value="">{{ __('admin.all_statuses') }}</option>
                                                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>{{__('admin.present')}}</option>
                                                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>{{__('admin.absent')}}</option>
                                                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>{{__('admin.late')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-primary btn-block" id="clear_filters">
                                                    <i class="fas fa-eraser mr-2"></i>{{ __('admin.clear') }}
                                                </button>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="degree_filter">Degree</label>
                                                <select class="form-control select2" id="degree_filter">
                                                    <option value="" selected>{{ __('admin.all_degrees') }}</option>
                                                    @for($i = 0; $i <= 10; $i++)
                                                        <option value="{{ $i }}">
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Attendance Table Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-primary">
                                {{-- <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-table mr-2"></i>{{ __('admin.attendance_statistics') }}
                                    </h3>
                                </div> --}}
                                <div class="card-body">
                                    <div id="attendance-table">
                                        @include('admin.attendances.partials.attendance-table')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

    function updateAttendanceTable() {
        var classId = $('#class_filter').val();
        var date = $('#date_filter').val();
        var status = $('#status_filter').val();
        var degree = $('#degree_filter').val();

        // Show loading state
        $('#attendance-table').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Loading attendance records...</p></div>');

        // Make AJAX request
        $.ajax({
            url: '{{ route('admin.attendances.index') }}',
            type: 'GET',
            data: {
                class_id: classId,
                date: date,
                status: status,
                degree: degree
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            success: function(response) {
                $('#attendance-table').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#attendance-table').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Error loading attendance records.</div>');
            }
        });
    }

    // Add event listeners for filters
    $('#class_filter').on('change', updateAttendanceTable);
    $('#date_filter').on('change', updateAttendanceTable);
    $('#status_filter').on('change', updateAttendanceTable);
    // $('#degree_filter').on('change', updateAttendanceTable);

    // Clear filters button
    $('#clear_filters').on('click', function() {
        $('#class_filter').val('').trigger('change');
        $('#date_filter').val('{{ date('Y-m-d') }}');
        $('#status_filter').val('').trigger('change');
        updateAttendanceTable();
    });

    // Delegate click on stat boxes to set filter via AJAX
    $(document).on('click', '.present', function(e) {
        e.preventDefault();
        $('#status_filter').val('present').trigger('change');
    });
    $(document).on('click', '.absent', function(e) {
        e.preventDefault();
        $('#status_filter').val('absent').trigger('change');
    });
    $(document).on('click', '.late', function(e) {
        e.preventDefault();
        $('#status_filter').val('late').trigger('change');
    });
    $(document).on('click', '.total', function(e) {
        e.preventDefault();
        $('#status_filter').val('').trigger('change');
    });

    // Initial load
    updateAttendanceTable();
});
</script>
@endsection
