@extends('layouts.admin')

@section('title', 'Werds Records')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.werd_management') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.werds.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('admin.add_werd') }}
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
                                                <label for="date_filter">{{ __('admin.date') }}</label>
                                                <input type="date" id="date_filter" class="form-control" value="{{ $date }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="class_filter">{{ __('admin.class') }}</label>
                                                <select id="class_filter" class="form-control select2">
                                                    <option value="">{{ __('admin.all_classes') }}</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status_filter">{{ __('admin.status') }}</label>
                                                <select id="status_filter" class="form-control select2">
                                                    <option value="">{{ __('admin.all_status') }}</option>
                                                    <option value="good" {{ request('status')=='good'?'selected':'' }}>{{ __('admin.good') }}</option>
                                                    <option value="average" {{ request('status')=='average'?'selected':'' }}>{{ __('admin.average') }}</option>
                                                    <option value="weak" {{ request('status')=='weak'?'selected':'' }}>{{ __('admin.weak') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="degree_filter">{{ __('admin.degree') }}</label>
                                                <select id="degree_filter" class="form-control select2">
                                                    <option value="">{{ __('admin.all_degrees') }}</option>
                                                    @for($i=1;$i<=10;$i++)<option value="{{ $i }}"{{ request('degree')==$i?'selected':'' }}>{{ $i }}/10</option>@endfor
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-primary btn-block" id="clear_filters"><i class="fas fa-eraser mr-2"></i>{{ __('admin.clear') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Werds Table Section -->
                    <div class="card card-outline card-primary">
                        <div class="card-body p-0">
                            <div id="werd-table">
                                {{-- @include('admin.werds.partials.werd-table') --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet" />
@endpush
<!-- Include JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        function updateWerdTable() {
            var classId = $('#class_filter').val();
            var date = $('#date_filter').val();
            var status = $('#status_filter').val();
            var degree = $('#degree_filter').val();

            // Show loading state
            $('#werd-table').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Loading werd records...</p></div>');

            // Make AJAX request
            $.ajax({
                url: '{{ route('admin.werds.index') }}',
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
                    $('#werd-table').html(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $('#werd-table').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Error loading werd records.</div>');
                }
            });
        }

        // Add event listeners for filters
        $('#class_filter').on('change', updateWerdTable);
        $('#date_filter').on('change', updateWerdTable);
        $('#status_filter').on('change', updateWerdTable);
        // $('#degree_filter').on('change', updateWerdTable);

        // Clear filters button
        $('#clear_filters').on('click', function() {
            $('#class_filter').val('').trigger('change');
            $('#date_filter').val('{{ date('Y-m-d') }}');
            $('#status_filter').val('').trigger('change');
            updateWerdTable();
        });

        // Delegate click on stat boxes to set filter via AJAX
        $(document).on('click', '.good', function(e) {
            e.preventDefault();
            $('#status_filter').val('good').trigger('change');
        });
        $(document).on('click', '.average', function(e) {
            e.preventDefault();
            $('#status_filter').val('average').trigger('change');
        });
        $(document).on('click', '.weak', function(e) {
            e.preventDefault();
            $('#status_filter').val('weak').trigger('change');
        });
        $(document).on('click', '.total', function(e) {
            e.preventDefault();
            $('#status_filter').val('').trigger('change');
        });

        // Initial load
        updateWerdTable();
    });
    </script>
@endsection
