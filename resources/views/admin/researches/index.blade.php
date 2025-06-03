@extends('layouts.admin')

@section('title', __('admin.research_management'))

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('admin.research_management') }}</h3>
      <div class="card-tools">
        <a href="{{ route('admin.researches.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> {{ __('admin.add_research') }}</a>
      </div>
    </div>
    <div class="card-body">
      <!-- Filters -->
      <div class="row mb-4">
        <div class="col-md-3">
          <label>{{ __('admin.date') }}</label>
          <input type="date" id="date_filter" class="form-control" value="{{ $date }}">
        </div>
        <div class="col-md-3">
          <label>{{ __('admin.class') }}</label>
          <select id="class_filter" class="form-control select2">
            <option value="">{{ __('admin.all_classes') }}</option>
            @foreach($classes as $class)
              <option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label>{{ __('admin.status') }}</label>
          <select id="status_filter" class="form-control select2">
            <option value="">{{ __('admin.all_status') }}</option>
            <option value="good" {{ request('status')=='good'?'selected':'' }}>{{ __('admin.good') }}</option>
            <option value="average" {{ request('status')=='average'?'selected':'' }}>{{ __('admin.average') }}</option>
            <option value="weak" {{ request('status')=='weak'?'selected':'' }}>{{ __('admin.weak') }}</option>
          </select>
        </div>
        <div class="col-md-1">
          <label>&nbsp;</label>
          <button type="button" class="btn btn-primary btn-block" id="clear_filters"><i class="fas fa-eraser mr-2"></i>{{ __('admin.clear') }}</button>
        </div>
      </div>

      <!-- Research Table Section -->
      <div class="card card-outline card-primary">
        <div class="card-body p-0">
          <div id="research-table">
            @include('admin.researches.partials.research-table')
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function(){
  $('.select2').select2({ theme: 'bootstrap4' });
  function updateResearchTable() {
    $('#research-table').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Loading research records...</p></div>');
    $.ajax({
      url: '{{ route('admin.researches.index') }}',
      type: 'GET',
      data: {
        class_id: $('#class_filter').val(),
        date: $('#date_filter').val(),
        status: $('#status_filter').val()
      },
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
      success: function(response) {
        $('#research-table').html(response);
      },
      error: function() {
        $('#research-table').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i>Error loading research records.</div>');
      }
    });
  }
  $('#class_filter,#date_filter,#status_filter').on('change', updateResearchTable);
  $('#clear_filters').on('click', function(){
    $('.select2').val('').trigger('change');
    $('#date_filter').val('{{ now()->format('Y-m-d') }}');
    updateResearchTable();
  });
  updateResearchTable();
});
</script>
@endpush
