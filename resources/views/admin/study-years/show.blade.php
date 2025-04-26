@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $studyYear->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.study-years.edit', $studyYear) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                        </a>
                        <a href="{{ route('admin.study-years.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl>
                                <dt>{{ __('admin.name') }}</dt>
                                <dd>{{ $studyYear->name }}</dd>

                                <dt>{{ __('admin.start_date') }}</dt>
                                <dd>{{ $studyYear->start_date->format('Y-m-d') }}</dd>

                                <dt>{{ __('admin.end_date') }}</dt>
                                <dd>{{ $studyYear->end_date->format('Y-m-d') }}</dd>

                                <dt>{{ __('admin.status') }}</dt>
                                <dd>
                                    <span class="badge badge-{{ $studyYear->is_active ? 'success' : 'danger' }}">
                                        {{ $studyYear->is_active ? __('admin.active') : __('admin.inactive') }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <h4 class="mt-4">{{ __('admin.terms') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.name') }}</th>
                                    <th>{{ __('admin.start_date') }}</th>
                                    <th>{{ __('admin.end_date') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studyYear->terms as $term)
                                    <tr>
                                        <td>{{ $term->name }}</td>
                                        <td>{{ $term->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $term->end_date->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $term->is_active ? 'success' : 'danger' }}">
                                                {{ $term->is_active ? __('admin.active') : __('admin.inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.study-terms.show', $term) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.study-terms.edit', $term) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('admin.no_terms_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h4 class="mt-4">{{ __('admin.classes') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.name') }}</th>
                                    <th>{{ __('admin.student_number') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studyYear->classes as $class)
                                    <tr>
                                        <td>{{ $class->name }}</td>
                                        <td>{{ $class->students->count() }}</td>
                                        <td>
                                            <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
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
</div>
@endsection
