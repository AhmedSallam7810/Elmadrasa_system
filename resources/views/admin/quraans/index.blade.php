@extends('layouts.admin')

@section('title', 'Quraan Records')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book-quran mr-2"></i>{{ __('admin.quraan') }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.quraans.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> {{ __('admin.record') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-body">
                                    <form id="filters-form" method="GET">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="date">{{ __('admin.date') }}</label>
                                                    <input type="date" class="form-control filter-input" id="date" name="date"
                                                        value="{{ request('date', now()->format('Y-m-d')) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="class_id">{{ __('admin.class') }}</label>
                                                    <select class="form-control select2 filter-input" id="class_id" name="class_id">
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
                                                    <label for="status">{{ __('admin.status') }}</label>
                                                    <select class="form-control select2 filter-input" id="status" name="status">
                                                        <option value="">{{ __('admin.all_statuses') }}</option>
                                                        <option value="good" {{ request('status') === 'good' ? 'selected' : '' }}>
                                                            {{ __('admin.good') }}
                                                        </option>
                                                        <option value="average" {{ request('status') === 'average' ? 'selected' : '' }}>
                                                            {{ __('admin.average') }}
                                                        </option>
                                                        <option value="weak" {{ request('status') === 'weak' ? 'selected' : '' }}>
                                                            {{ __('admin.weak') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
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
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-primary btn-block" id="clear_filters">
                                                        <i class="fas fa-eraser mr-2"></i>{{ __('admin.clear') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Records Table -->
                    <div id="records-table">
                        @include('admin.quraans.partials.quraan-table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
            theme: 'bootstrap4'
        });
    // Add debounce function to prevent too many requests
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Function to update table content
    function updateTable() {
        var form = $('#filters-form');
        console.log(form.serialize());
        $.ajax({
            url: '{{ route('admin.quraans.index') }}',
            data: form.serialize(),
            beforeSend: function() {
                // Show loading indicator
                $('#records-table').addClass('opacity-50');
            },
            success: function(response) {
                $('#records-table').html(response);
            },
            complete: function() {
                // Hide loading indicator
                $('#records-table').removeClass('opacity-50');
            }
        });
    }

    // Debounced version of updateTable with 300ms delay
    const debouncedUpdate = debounce(updateTable, 300);

    // Handle all filter changes
    $('.filter-input').on('change', debouncedUpdate);

    // Special handling for Select2
    $('.select2.filter-input').on('select2:select select2:unselect', debouncedUpdate);

    // Clear filters button
    $('#clear_filters').on('click', function() {
        $('.filter-input').val('').trigger('change');
        $('#date').val('{{ now()->format('Y-m-d') }}');
        updateTable();
    });

    // Add CSS for loading state
    $('<style>')
        .text('.opacity-50 { opacity: 0.5; pointer-events: none; }')
        .appendTo('head');
});
</script>
