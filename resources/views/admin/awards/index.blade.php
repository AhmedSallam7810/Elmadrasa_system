@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('admin.awards') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.awards.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> {{ __('admin.create_award') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('admin.title') }}</th>
                                    <th>{{ __('admin.class') }}</th>
                                    <th>{{ __('admin.points') }}</th>
                                    <th>{{ __('admin.status') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($awards as $award)
                                    <tr>
                                        <td>{{ $award->title }}</td>
                                        <td>{{ $award->class->name }}</td>
                                        <td>{{ $award->points }}</td>
                                        <td>
                                            <span class="badge badge-{{ $award->is_active ? 'success' : 'danger' }}">
                                                {{ $award->is_active ? __('admin.active') : __('admin.inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.awards.show', $award) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye">عرض</i>
                                            </a>
                                            <a href="{{ route('admin.awards.edit', $award) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit">تعديل</i>
                                            </a>
                                            <form action="{{ route('admin.awards.destroy', $award) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                                                    <i class="fas fa-trash">حذف</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('admin.no_awards_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $awards->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
