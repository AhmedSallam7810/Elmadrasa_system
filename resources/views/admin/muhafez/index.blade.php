@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.muhafezs') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.muhafezs.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus mr-1"></i>{{ __('admin.add_new_muhafez') }}
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
                                <th>{{ __('admin.phone') }}</th>
                                <th>{{ __('admin.student_count') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($muhafezs as $muhafez)
                                <tr>
                                    <td>{{ $muhafez->id }}</td>
                                    <td>{{ $muhafez->name }}</td>
                                    <td>{{ $muhafez->phone }}</td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $muhafez->students_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $muhafez->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                                            {{ __('admin.' . $muhafez->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.muhafezs.enroll-students', $muhafez->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-user-plus mr-1"></i>{{ __('admin.students') }}
                                        </a>
                                        {{-- <a href="{{ route('admin.muhafezs.show', $muhafez->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye mr-1"></i>{{ __('admin.show') }}
                                        </a> --}}
                                        <a href="{{ route('admin.muhafezs.edit', $muhafez->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit mr-1"></i>{{ __('admin.edit') }}
                                        </a>
                                        <form action="{{ route('admin.muhafezs.destroy', $muhafez->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('admin.confirm_delete_muhafez') }}')">
                                                <i class="fas fa-trash mr-1"></i>{{ __('admin.delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('admin.no_muhafezs_found') }}</td>
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
