<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>{{ __('admin.total_records') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-book"></i>
            </div>
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
            @forelse($quraans as $quraan)
                <tr>
                    <td>{{ $quraan->date->format('Y-m-d') }}</td>
                    <td>{{ $quraan->student->name }}</td>
                    <td>{{ $quraan->student->classes->first()->name ?? 'Not Assigned' }}</td>
                    <td>
                        @if($quraan->status === 'good')
                            <span class="badge badge-success">{{ __('admin.good') }}</span>
                        @elseif($quraan->status === 'average')
                            <span class="badge badge-warning">{{ __('admin.average') }}</span>
                        @else
                            <span class="badge badge-danger">{{ __('admin.weak') }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">
                            {{ $quraan->degree }} / 10
                        </span>
                    </td>
                    <td>{{ $quraan->notes }}</td>
                    <td>
                        <a href="{{ route('admin.quraans.show', $quraan) }}"
                            class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> {{__('admin.view')}}
                        </a>
                        <a href="{{ route('admin.quraans.edit', $quraan) }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{__('admin.edit')}}
                        </a>
                        <form action="{{ route('admin.quraans.destroy', $quraan) }}"
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
                    <td colspan="7" class="text-center">{{ __('admin.no_quraan_records_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $quraans->appends(request()->query())->links() }}
</div>
