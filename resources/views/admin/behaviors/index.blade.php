@extends('layouts.admin')

@section('title', 'Behavior Records')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.behavior_management') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.behaviors.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('admin.add') }}
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

                    <!-- Behavior Table Section -->
                    <div class="card card-outline card-primary">
                        <div class="card-body p-0">
                            <div id="behavior-table">
                                {{-- @include('admin.behaviors.partials.behavior-table') --}}
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap4' });
    function updateBehaviorTable() {
        $('#behavior-table').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Loading behavior records...</p></div>');
        $.ajax({
            url: '{{ route('admin.behaviors.index') }}',
            type: 'GET',
            data: { class_id: $('#class_filter').val(), date: $('#date_filter').val(), status: $('#status_filter').val() },
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
            success: function(response) { $('#behavior-table').html(response); },
            error: function() { $('#behavior-table').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Error loading behavior records.</div>'); }
        });
    }
    $('#class_filter,#date_filter,#status_filter').on('change', updateBehaviorTable);
    $('#clear_filters').on('click', function() { $('#class_filter,#status_filter').val('').trigger('change'); $('#date_filter').val('{{ date('Y-m-d') }}'); updateBehaviorTable(); });
    $(document).on('click', '.good,.average,.weak,.total', function(e){ e.preventDefault(); $('#status_filter').val($(this).hasClass('total')?'':$(this).data('status')).trigger('change'); });
    updateBehaviorTable();
});
</script>
@endsection
