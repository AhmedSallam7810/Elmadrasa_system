@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.edit_werd') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.werds.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.werds.update', $werd) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.date') }}</label>
                                    <input type="text" class="form-control" value="{{ $werd->date->format('Y-m-d') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('admin.student') }}</label>
                                    <input type="text" class="form-control" value="{{ $werd->student->name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('admin.status') }}</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="good" {{ $werd->status === 'good' ? 'selected' : '' }}>{{ __('admin.good') }}</option>
                                        <option value="average" {{ $werd->status === 'average' ? 'selected' : '' }}>{{ __('admin.average') }}</option>
                                        <option value="weak" {{ $werd->status === 'weak' ? 'selected' : '' }}>{{ __('admin.weak') }}</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('admin.degree') }}</label>
                                    <input type="number" name="degree" id="degree"
                                           class="form-control @error('degree') is-invalid @enderror"
                                           value="{{ old('degree', $werd->degree) }}"
                                           min="0" max="10" required>
                                    @error('degree')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('admin.notes') }}</label>
                                    <input type="text" name="notes" id="notes"
                                           class="form-control @error('notes') is-invalid @enderror"
                                           value="{{ old('notes', $werd->notes) }}"
                                           placeholder="{{ __('admin.optional_notes') }}">
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('admin.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
