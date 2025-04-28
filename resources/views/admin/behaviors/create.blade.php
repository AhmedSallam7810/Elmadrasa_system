@extends('layouts.admin')

@section('title', __('admin.record_behavior'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('admin.record_behavior') }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.behaviors.index') }}" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> {{ __('admin.back') }}</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.behaviors.store') }}" method="POST" id="behavior-form">
                @csrf
                <input type="hidden" name="date" id="form_date" value="{{ request('date', $date) }}">
                <input type="hidden" name="class_id" id="form_class_id" value="{{ request('class_id') }}">

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
                </div>

                @if($students->isEmpty())
                    <div class="alert alert-info">{{ __('admin.no_students_found_without_records') }}</div>
                @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>{{ __('admin.student_name') }}</th>
                                <th>{{ __('admin.class') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.degree') }}</th>
                                <th>{{ __('admin.notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td><input type="checkbox" class="student-checkbox" name="student_ids[]" value="{{ $student->id }}" checked></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->classes->pluck('name')->implode(', ') }}</td>
                                <td>
                                    <select name="status[{{ $student->id }}]" class="form-control status-select" data-student-id="{{ $student->id }}">
                                        <option value="good">{{ __('admin.good') }}</option>
                                        <option value="average">{{ __('admin.average') }}</option>
                                        <option value="weak">{{ __('admin.weak') }}</option>
                                    </select>
                                </td>
                                <td><input type="number" name="degree[{{ $student->id }}]" class="form-control degree-input" value="10" min="0" max="10" step="0.5"></td>
                                <td><input type="text" name="notes[{{ $student->id }}]" class="form-control"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                    <a href="{{ route('admin.behaviors.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('.select2').select2({ theme: 'bootstrap4' });
    $('#select-all').change(function(){ $('.student-checkbox').prop('checked', $(this).prop('checked')); });
    $('.status-select').change(function(){ var id=$(this).data('student-id'), val=$(this).val(); var inp=$('.degree-input[data-student-id="'+id+'"]'); if(val=='weak') inp.val(3); else if(val=='average') inp.val(7); else inp.val(10); });
    $('.status-select').each(function(){ $(this).data('student-id', $(this).attr('name').match(/\d+/)[0]); });
    $('#class_id,#date').change(function(){ window.location.href = '{{ route('admin.behaviors.create') }}?class_id='+$('#class_id').val()+'&date='+$('#date').val(); });
});
</script>
@endpush
