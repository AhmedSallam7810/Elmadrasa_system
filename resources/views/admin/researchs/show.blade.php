@extends('layouts.admin')

@section('title', __('admin.view_research'))

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('admin.view_research') }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.researchs.index') }}" class="btn btn-default btn-sm">
                    <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                </a>
            </div>
        </div>
        <div class="card-body">
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
                    <label>{{ __('admin.status') }}</label>
                    <input type="text" class="form-control" value="{{ __('admin.' . $research->status) }}" readonly>
                </div>
                <div class="form-group col-md-4">
                    <label>{{ __('admin.degree') }}</label>
                    <input type="text" class="form-control" value="{{ $research->degree }} / 20" readonly>
                </div>
            </div>
            <div class="form-group">
                <label>{{ __('admin.notes') }}</label>
                <textarea class="form-control" rows="3" readonly>{{ $research->notes }}</textarea>
            </div>
        </div>
    </div>
</div>
@endsection
