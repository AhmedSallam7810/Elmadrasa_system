@extends('layouts.admin')

@section('title', __('admin.summary_management'))

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('admin.summary_management') }}</h3>
      <div class="card-tools">
        <a href="{{ route('admin.summaries.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> {{ __('admin.add_summary') }}</a>
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
      @include('admin.summaries.partials.summary-table')
    </div>
  </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){
  $('.select2').select2({ theme: 'bootstrap4' });
  function updateTable(){
    $('#summary-table').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
    $.get('{{ route('admin.summaries.index') }}', {class_id:$('#class_filter').val(),date:$('#date_filter').val(),status:$('#status_filter').val()}, function(res){
      $('#summary-table').html(res);
    });
  }
  $('#class_filter,#date_filter,#status_filter').on('change', updateTable);
  $('#clear_filters').on('click', function(){
    $('.select2').val('').trigger('change');
    $('#date_filter').val('{{ now()->format('Y-m-d') }}');
    updateTable();
  });
  updateTable();
});
</script>
