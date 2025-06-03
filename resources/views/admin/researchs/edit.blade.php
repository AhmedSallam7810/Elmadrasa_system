@extends('layouts.admin')

@section('title', __('admin.edit_research'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('admin.edit_research') }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.researchs.index') }}" class="btn btn-default btn-sm"><i class="fas fa-arrow-left"></i> {{ __('admin.back') }}</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.researchs.update', $research) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label>{{ __('admin.student_name') }}</label>
                    <input type="text" class="form-control" value="{{ $research->student->name }}" readonly>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>{{ __('admin.date') }}</label>
                        <input type="text" class="form-control" value="{{ \Illuminate\Support\Carbon::parse($research->date)->format('Y-m-d') }}" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="status">{{ __('admin.status') }}</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="good" {{ $research->status=='good'?'selected':'' }}>{{ __('admin.good') }}</option>
                            <option value="average" {{ $research->status=='average'?'selected':'' }}>{{ __('admin.average') }}</option>
                            <option value="weak" {{ $research->status=='weak'?'selected':'' }}>{{ __('admin.weak') }}</option>
                        </select>
                        @error('status')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="degree">{{ __('admin.degree') }}</label>
                        <input type="number" name="degree" id="degree" class="form-control @error('degree') is-invalid @enderror" value="{{ old('degree', $research->degree) }}" min="0" max="10" step="0.5">
                        @error('degree')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">{{ __('admin.notes') }}</label>
                    <input type="text" name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" value="{{ old('notes', $research->notes) }}">
                    @error('notes')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn btn-primary">{{ __('admin.save_changes') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
