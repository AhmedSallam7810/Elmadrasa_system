@extends('layouts.admin')

@section('title', __('admin.add_summary'))

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('admin.add_summary') }}</h3>
      <div class="card-tools">
        <div class="d-flex">
          <a href="{{ route('admin.summaries.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
          </a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.summaries.store') }}" method="POST" id="summary-form">
        @csrf
        <input type="hidden" name="date" value="{{ request('date', $date) }}">
        <input type="hidden" name="class_id" value="{{ request('class_id') }}">

        <div class="row mb-4">
          <div class="col-md-3">
            <label for="date">{{ __('admin.date') }}</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ request('date', $date) }}">
          </div>
          <div class="col-md-3">
            <label for="class_id">{{ __('admin.class') }}</label>
            <select id="class_id" class="form-control select2">
              <option value="">{{ __('admin.all_classes') }}</option>
              @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id')==$class->id?'selected':'' }}>{{ $class->name }}</option>
              @endforeach
            </select>
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

        @include('admin.summaries.partials.student-table')
      </form>
    </div>
  </div>
</div>

<!-- Excel Upload Modal -->
<div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="uploadExcelModalLabel">{{ __('admin.upload_summaries_excel') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.summaries.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="date" id="upload_date">
        <div class="modal-body">
          <div class="form-group">
            <label for="excel_file">{{ __('admin.excel_file') }}</label>
            <input type="file" class="form-control-file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
            <small class="form-text text-muted">{{ __('admin.summaries_excel_format_help') }}</small>
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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(function(){
  $('.select2').select2({ theme:'bootstrap4' });
  $('#select-all').change(function(){
    $('.student-checkbox').prop('checked', $(this).prop('checked'));
  });
  $('.status-select').on('change', function(){
    var id = $(this).data('student-id'), val = $(this).val();
    var inp = $('.degree-input[data-student-id="'+id+'"]');
    if(val=='weak') inp.val(3);
    else if(val=='average') inp.val(7);
    else inp.val(10);
  });
  $('#class_id,#date').change(function(){
    window.location.href = '{{ route('admin.summaries.create') }}?class_id='+$('#class_id').val()+'&date='+$('#date').val();
  });
  // Export Excel template
  $('#export-excel').click(function() {
    const classId = $('#class_id').val();
    const date = $('#date').val();

    if (!date) {
      toastr.error('{{ __("admin.please_select_date") }}');
      return;
    }

    window.location.href = `{{ route('admin.summaries.download-template') }}?class_id=${classId}&date=${date}`;
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
  });
});
</script>
@endpush
