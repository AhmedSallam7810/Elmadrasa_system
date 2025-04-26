@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.study_years') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.study-years.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> {{ __('admin.create_study_year') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.name') }}</th>
                                    <th>{{ __('admin.start_date') }}</th>
                                    <th>{{ __('admin.end_date') }}</th>
                                    <th>{{ __('admin.terms') }}</th>
                                    <th>{{ __('admin.classes') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studyYears as $studyYear)
                                    <tr>
                                        <td>{{ $studyYear->name }}</td>
                                        <td>{{ $studyYear->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $studyYear->end_date->format('Y-m-d') }}</td>
                                        <td>{{ $studyYear->terms->count() }}</td>
                                        <td>{{ $studyYear->classes->count() }}</td>
                                        <td>
                                            <span class="badge badge-{{ $studyYear->is_active ? 'success' : 'danger' }}">
                                                {{ $studyYear->is_active ? __('admin.active') : __('admin.inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.study-years.show', $studyYear) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.study-years.edit', $studyYear) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.study-years.destroy', $studyYear) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('admin.no_study_years_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $studyYears->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
