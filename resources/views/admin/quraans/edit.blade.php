@extends('layouts.admin')

@section('title', __('admin.edit_quraan'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.edit_quraan') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.quraans.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.quraans.update', $quraan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">{{ __('admin.date') }}</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date" value="{{ old('date', $quraan->date->format('Y-m-d')) }}" readonly>
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id">{{ __('admin.student') }}</label>
                                    <input type="text" class="form-control" value="{{ $quraan->student->name }}" readonly>
                                    <input type="hidden" name="student_id" value="{{ $quraan->student_id }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">{{ __('admin.status') }}</label>
                                    <select class="form-control @error('status') is-invalid @enderror"
                                        id="status" name="status" required>
                                        <option value="good" {{ old('status', $quraan->status) == 'good' ? 'selected' : '' }}>{{ __('admin.good') }}</option>
                                        <option value="average" {{ old('status', $quraan->status) == 'average' ? 'selected' : '' }}>{{ __('admin.average') }}</option>
                                        <option value="weak" {{ old('status', $quraan->status) == 'weak' ? 'selected' : '' }}>{{ __('admin.weak') }}</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="degree">{{ __('admin.degree') }} (0-30)</label>
                                    <input type="number" class="form-control @error('degree') is-invalid @enderror"
                                        id="degree" name="degree"
                                        value="{{ old('degree', $quraan->degree) }}"
                                        min="0" max="30" step="1" required>
                                    @error('degree')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="notes">{{ __('admin.notes') }}</label>
                                    <input type="text" class="form-control @error('notes') is-invalid @enderror"
                                        id="notes" name="notes"
                                        value="{{ old('notes', $quraan->notes) }}">
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('admin.update') }}</button>
                            <a href="{{ route('admin.quraans.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle status change to update degree
    $('#status').change(function() {
        var degreeInput = $('#degree');

        if ($(this).val() === 'weak') {
            degreeInput.val('0');
            degreeInput.prop('disabled', true);
        } else if ($(this).val() === 'good') {
            degreeInput.val('30');
            degreeInput.prop('disabled', false);
        } else if ($(this).val() === 'average') {
            degreeInput.val('15');
            degreeInput.prop('disabled', false);
        }
    });

    // Initialize degree value based on initial status
    $('#status').trigger('change');
});
</script>
@endpush
