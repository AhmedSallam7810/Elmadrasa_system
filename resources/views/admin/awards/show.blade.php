@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $award->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.awards.edit', $award) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                        </a>
                        <a href="{{ route('admin.awards.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl>
                                <dt>{{ __('admin.title') }}</dt>
                                <dd>{{ $award->title }}</dd>

                                <dt>{{ __('admin.description') }}</dt>
                                <dd>{{ $award->description }}</dd>

                                <dt>{{ __('admin.points') }}</dt>
                                <dd>{{ $award->points }}</dd>

                                <dt>{{ __('admin.class') }}</dt>
                                <dd>{{ $award->class->name }}</dd>

                                <dt>{{ __('admin.status') }}</dt>
                                <dd>
                                    <span class="badge badge-{{ $award->is_active ? 'success' : 'danger' }}">
                                        {{ $award->is_active ? __('admin.active') : __('admin.inactive') }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <h4 class="mt-4">{{ __('admin.students_completion') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.student_name') }}</th>
                                    <th>{{ __('admin.completion_status') }}</th>
                                    <th>{{ __('admin.completed_at') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($award->students as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            <span class="badge badge-{{ $student->pivot->is_completed ? 'success' : 'warning' }}">
                                                {{ $student->pivot->is_completed ? __('admin.completed') : __('admin.pending') }}
                                            </span>
                                        </td>
                                        <td>{{ $student->pivot->completed_at ? $student->pivot->completed_at->format('Y-m-d H:i') : '-' }}</td>
                                        <td>
                                            @if(!$student->pivot->is_completed)
                                                <form action="{{ route('admin.awards.complete', $award) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check"></i> {{ __('admin.mark_completed') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">{{ __('admin.no_students_assigned') }}</td>
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
