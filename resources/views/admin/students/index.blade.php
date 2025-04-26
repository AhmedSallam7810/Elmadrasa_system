@extends('layouts.admin')

@section('title', __('admin.students'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>{{ __('admin.students') }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> {{ __('admin.add_new_student') }}
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
                                                <label for="search">{{ __('admin.search_by_name') }}</label>
                                                <input type="text" class="form-control" id="search" placeholder="{{ __('admin.enter_student_name') }}" value="{{ request('search') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="class_filter">{{ __('admin.filter_by_class') }}</label>
                                                <select class="form-control select2" id="class_filter">
                                                    <option value="">{{ __('admin.all_classes') }}</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ request('class') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="muhafez_filter">{{ __('admin.filter_by_muhafez') }}</label>
                                                <select class="form-control select2" id="muhafez_filter">
                                                    <option value="">{{ __('admin.all_muhafezs') }}</option>
                                                    @foreach($muhafezs as $muhafez)
                                                        <option value="{{ $muhafez->id }}" {{ request('muhafez') == $muhafez->id ? 'selected' : '' }}>
                                                            {{ $muhafez->name }}
                                                        </option>
                                                    @endforeach
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Table Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-primary">
                                <div class="card-body">
                                    <div id="students-table">
                                        @include('admin.students.partials.students-table')
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

    // Function to update table with AJAX
    function updateStudentsTable() {
        var search = $('#search').val();
        var classId = $('#class_filter').val();
        var muhafezId = $('#muhafez_filter').val();

        // Show loading state
        $('#students-table').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Loading students...</p></div>');

        // Make AJAX request
        $.ajax({
            url: '{{ route('admin.students.index') }}',
            type: 'GET',
            data: {
                search: search,
                class: classId,
                muhafez: muhafezId
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            },
            success: function(response) {
                $('#students-table').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                $('#students-table').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Error loading students.</div>');
            }
        });
    }

    // Add event listeners for filters
    var filterTimeout;
    $('#search').on('input', function() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(updateStudentsTable, 500);
    });
    $('#class_filter').on('change', updateStudentsTable);
    $('#muhafez_filter').on('change', updateStudentsTable);

    // Clear filters
    $('#clear_filters').on('click', function() {
        $('#search').val('');
        $('#class_filter').val('').trigger('change');
        $('#muhafez_filter').val('').trigger('change');
        updateStudentsTable();
    });

    // Initial load
    updateStudentsTable();
});
</script>
@endsection
