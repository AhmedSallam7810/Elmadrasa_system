<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>{{ __('admin.total_records') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['good'] }}</h3>
                <p>{{ __('admin.good') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['average'] }}</h3>
                <p>{{ __('admin.average') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-adjust"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['weak'] }}</h3>
                <p>{{ __('admin.weak') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-times"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('admin.date') }}</th>
                <th>{{ __('admin.student_name') }}</th>
                <th>{{ __('admin.class') }}</th>
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.degree') }}</th>
                <th>{{ __('admin.notes') }}</th>
                <th>{{ __('admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($werds as $werd)
                <tr>
                    <td>{{ $werd->date->format('Y-m-d') }}</td>
                    <td>{{ $werd->student->name }}</td>
                    <td>{{ $werd->student->classes->first()->name ?? __('admin.not_assigned') }}</td>
                    <td>
                        @if($werd->status === 'good')
                            <span class="badge badge-success">{{ __('admin.good') }}</span>
                        @elseif($werd->status === 'average')
                            <span class="badge badge-warning">{{ __('admin.average') }}</span>
                        @else
                            <span class="badge badge-danger">{{ __('admin.weak') }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">
                            {{ $werd->degree }} / 10
                        </span>
                    </td>
                    <td>{{ $werd->notes }}</td>
                    <td>
                        <a href="{{ route('admin.werds.show', $werd) }}"
                            class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> {{__('admin.view')}}
                        </a>
                        <a href="{{ route('admin.werds.edit', $werd) }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{__('admin.edit')}}
                        </a>
                        <form action="{{ route('admin.werds.destroy', $werd) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                                <i class="fas fa-trash"></i> {{__('admin.delete')}}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('admin.no_werd_records_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $werds->appends(request()->query())->links() }}
</div>
