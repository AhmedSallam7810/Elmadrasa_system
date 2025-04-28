@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.classes') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus">{{ __('admin.add_new_class') }}</i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('admin.name') }}</th>
                                <th>{{ __('admin.student_count') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                                <tr>
                                    <td>{{ $class->id }}</td>
                                    <td>{{ $class->name }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $class->students->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- <a href="{{ route('admin.classes.enroll-students', $class->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-user-plus"></i> Enroll Students
                                        </a> --}}

                                        <a href="{{ route('admin.classes.show', $class->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye">{{ __('admin.students') }}</i>
                                        </a>
                                        <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit">{{ __('admin.edit') }}</i>
                                        </a>
                                        <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?')">
                                                <i class="fas fa-trash">{{ __('admin.delete') }}</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">{{ __('admin.no_classes_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
