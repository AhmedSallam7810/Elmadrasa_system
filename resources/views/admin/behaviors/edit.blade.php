@extends('layouts.admin')

@section('title', __('admin.edit_behavior'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('admin.edit_behavior') }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.behaviors.index') }}" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> {{ __('admin.back') }}</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.behaviors.update', $behavior) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>{{ __('admin.student_name') }}</label>
                    <input type="text" class="form-control" value="{{ $behavior->student->name }}" readonly>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>{{ __('admin.date') }}</label>
                        <input type="text" class="form-control" value="{{ \Illuminate\Support\Carbon::parse($behavior->date)->format('Y-m-d') }}" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="status">{{ __('admin.status') }}</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="good" {{ $behavior->status=='good'?'selected':'' }}>{{ __('admin.good') }}</option>
                            <option value="average" {{ $behavior->status=='average'?'selected':'' }}>{{ __('admin.average') }}</option>
                            <option value="weak" {{ $behavior->status=='weak'?'selected':'' }}>{{ __('admin.weak') }}</option>
                        </select>
                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="degree">{{ __('admin.degree') }} (0-10)</label>
                        <input type="number" name="degree" id="degree" class="form-control @error('degree') is-invalid @enderror" value="{{ old('degree', $behavior->degree) }}" min="0" max="10" required>
                        @error('degree')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">{{ __('admin.notes') }}</label>
                    <input type="text" name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $behavior->notes) }}">
                    @error('notes')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('admin.save_changes') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
