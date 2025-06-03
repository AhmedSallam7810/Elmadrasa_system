@extends('layouts.admin')

@section('title', __('admin.add_student'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.add_student') }}</h3>
                    <div class="card-tools">
                        <div class="d-flex">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary btn-sm mr-2">
                                <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                            </a>
                            <button type="button" class="btn btn-success mr-2" id="export-excel">
                                <i class="fas fa-file-excel mr-1"></i> {{ __('admin.export_excel') }}
                            </button>
                            <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#uploadExcelModal">
                                <i class="fas fa-upload mr-1"></i> {{ __('admin.upload_excel') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf
                        <!-- Existing form fields -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{ __('admin.name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">{{ __('admin.email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">{{ __('admin.password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">{{ __('admin.confirm_password') }}</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">{{ __('admin.phone') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">{{ __('admin.status') }}</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">{{ __('admin.address') }}</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Excel Upload Modal -->
<div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadExcelModalLabel">{{ __('admin.upload_students_excel') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excel_file">{{ __('admin.excel_file') }}</label>
                        <input type="file" class="form-control-file" id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                        <small class="form-text text-muted">{{ __('admin.students_excel_format_help') }}</small>
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


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Export Excel template
    $('#export-excel').click(function() {
        window.location.href = '{{ route('admin.students.download-template') }}';
    });
});
</script>
