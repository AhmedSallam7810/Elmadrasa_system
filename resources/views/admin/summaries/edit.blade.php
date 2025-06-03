@extends('layouts.admin')

@section('title', __('admin.edit_summary'))

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ __('admin.edit_summary') }}</h3>
      <div class="card-tools">
        <a href="{{ route('admin.summaries.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> {{ __('admin.back') }}</a>
      </div>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.summaries.update', $summary) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
          <label>{{ __('admin.student_name') }}</label>
          <input type="text" class="form-control" value="{{ $summary->student->name }}" readonly>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label>{{ __('admin.date') }}</label>
            <input type="text" class="form-control" value="{{ $summary->date->format('Y-m-d') }}" readonly>
          </div>
          <div class="form-group col-md-4">
            <label>{{ __('admin.status') }}</label>
            <select name="status" class="form-control">
              <option value="good" {{ $summary->status=='good'?'selected':'' }}>{{ __('admin.good') }}</option>
              <option value="average" {{ $summary->status=='average'?'selected':'' }}>{{ __('admin.average') }}</option>
              <option value="weak" {{ $summary->status=='weak'?'selected':'' }}>{{ __('admin.weak') }}</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label>{{ __('admin.degree') }}</label>
            <input type="number" name="degree" class="form-control" value="{{ old('degree',$summary->degree) }}" min="0" max="10" step="0.5">
          </div>
        </div>
        <div class="form-group">
          <label>{{ __('admin.notes') }}</label>
          <textarea name="notes" class="form-control" rows="3">{{ old('notes',$summary->notes) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('admin.save_changes') }}</button>
      </form>
    </div>
  </div>
</div>
@endsection
