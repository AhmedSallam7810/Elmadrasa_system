@extends('layouts.admin')

@section('title', __('admin.enroll_students'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-2"></i>{{ __('admin.students_to_muhafez', ['name' => $muhafez->name]) }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.muhafezs.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i>{{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.muhafezs.enroll-students.store', $muhafez->id) }}" method="POST">
                        @csrf

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">{{ __('admin.add_student') }}</h4>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="student_id">{{ __('admin.select_student') }}</label>
                                            <select class="form-control select2  @error('student_id') is-invalid @enderror"
                                                    id="student_id"
                                                    name="student_id">
                                                <option value="">{{ __('admin.select_student') }}</option>
                                        @foreach($availableStudents as $student)
                                            <option value="{{ $student->id }}"
                                                {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                {{ $student->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mt-1"></div>
                                <button type="submit" class="btn btn-primary mt-4">
                                    <i class="fas fa-save mr-1"></i>{{ __('admin.add') }}
                                </button>
                            </div>
                        </div>
                        </div>
                    </div>


                    </form>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('admin.currently_enrolled_students') }}</h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('admin.id') }}</th>
                                                <th>{{ __('admin.name') }}</th>
                                                <th>{{ __('admin.phone') }}</th>
                                                <th>{{ __('admin.class') }}</th>
                                                <th>{{ __('admin.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($enrolledStudents as $student)
                                                <tr>
                                                    <td>{{ $student->id }}</td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->phone ?? __('admin.not_available') }}</td>
                                                    <td>
                                                        @if($student->classes->isNotEmpty())
                                                            {{ $student->classes->pluck('name')->implode(', ') }}
                                                        @else
                                                            {{ __('admin.no_classes') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('admin.muhafezs.enroll-students.remove', [$muhafez->id, $student->id]) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('{{ __('admin.confirm_remove_student') }}')">
                                                                <i class="fas fa-user-minus mr-1"></i>{{ __('admin.remove') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">{{ __('admin.no_students_enrolled') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    console.log('ready');
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
});
</script>
@endsection
