@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $class->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back_to_list') }}
                        </a>
                        {{-- <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a> --}}

                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">#</th>
                                    <td>{{ $class->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('admin.name') }}</th>
                                    <td>{{ $class->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('admin.created_at') }}</th>
                                    <td>{{ $class->created_at->format('Y/m/d ') }}</td>
                                </tr>
                                {{-- <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $class->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr> --}}
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('admin.description') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $class->description ?? __('admin.no_description_available') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">{{ __('admin.enrolled_students') }}</h4>
                                </div>
                                <div class="card-body">
                                    @if($class->students->count() > 0)
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('admin.name') }}</th>
                                                    <th>{{ __('admin.phone') }}</th>
                                                    <th>{{ __('admin.enrolled_at') }}</th>
                                                    {{-- <th>{{ __('admin.status') }}</th> --}}
                                                    <th>{{ __('admin.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($class->students as $student)
                                                    <tr>
                                                        <td>{{ $student->id }}</td>
                                                        <td>{{ $student->name }}</td>
                                                        <td>{{ $student->phone }}</td>
                                                        <td>{{ $student->pivot->enrolled_at }}</td>
                                                        {{-- <td>
                                                            @if($student->pivot->is_active)
                                                                <span class="badge badge-success">{{ __('admin.active') }}</span>
                                                            @else
                                                                <span class="badge badge-danger">{{ __('admin.inactive') }}</span>
                                                            @endif
                                                        </td> --}}
                                                        <td>
                                                            <form action="{{ route('admin.classes.remove-student', [$class->id, $student->id]) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this student from the class?')">
                                                                    <i class="fas fa-user-minus"></i> {{ __('admin.remove') }}
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-center">No students enrolled in this class.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
