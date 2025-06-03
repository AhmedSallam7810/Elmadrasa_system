@extends('layouts.admin')

@section('title', __('admin.research_management'))
@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header ">
      <h3 class="card-title">{{ __('admin.research_management') }}</h3>
      <a href="{{ route('admin.researchs.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> {{ __('admin.record') }}</a>
    </div>
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-3"><input type="date" id="date_filter" class="form-control" value="{{ $date }}"></div>
        <div class="col-md-3">
          <select id="class_filter" class="form-control select2">
            <option value="">{{ __('admin.all_classes') }}</option>
            @foreach($classes as $class)
              <option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <select id="status_filter" class="form-control select2">
            <option value="">{{ __('admin.all_status') }}</option>
            <option value="good" {{ request('status')=='good'?'selected':'' }}>{{ __('admin.good') }}</option>
            <option value="average" {{ request('status')=='average'?'selected':'' }}>{{ __('admin.average') }}</option>
            <option value="weak" {{ request('status')=='weak'?'selected':'' }}>{{ __('admin.weak') }}</option>
          </select>
        </div>
        <div class="col-md-1"><button id="clear_filters" class="btn btn-primary"><i class="fas fa-eraser"></i> {{ __('admin.clear') }}</button></div>
      </div>
      <div id="research-table">@include('admin.researchs.partials.research-table', ['researchs'=>$researchs,'stats'=>$stats,'date'=>$date])</div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){
  $('.select2').select2({theme:'bootstrap4'});
  function loadTable(){
    $.get('{{ route('admin.researchs.index') }}',{class_id:$('#class_filter').val(),date:$('#date_filter').val(),status:$('#status_filter').val()},function(html){$('#research-table').html(html);});
  }
  $('#class_filter,#date_filter,#status_filter').change(loadTable);
  $('#clear_filters').click(function(){$('#class_filter,#status_filter').val('').trigger('change');$('#date_filter').val('{{ date('Y-m-d') }}');loadTable();});
});
</script>
@endsection
