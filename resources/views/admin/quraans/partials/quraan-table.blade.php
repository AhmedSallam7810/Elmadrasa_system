<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg col-4 mb-4">
        <div class="card text-white bg-secondary h-100 shadow rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0 text-white">{{ $stats['total_students'] }}</h3>
                    <p class="card-text small text-white">{{ __('admin.students_according_to_classes') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small total">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg col-4 mb-4">
        <div class="card text-white bg-info h-100 shadow rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0 text-white">{{ $stats['total'] }}</h3>
                    <p class="card-text small text-white">{{ __('admin.total_records') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small total">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg col-4 mb-4">
        <div class="card text-white bg-success h-100 shadow rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0 text-white">{{ $stats['good'] }}</h3>
                    <p class="card-text small text-white">{{ __('admin.good') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small total">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg col-4 mb-4">
        <div class="card text-white bg-warning h-100 shadow rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0 text-white">{{ $stats['average'] }}</h3>
                    <p class="card-text small text-white">{{ __('admin.average') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-adjust"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small total">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg col-4 mb-4">
        <div class="card text-white bg-danger h-100 shadow rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0 text-white">{{ $stats['weak'] }}</h3>
                    <p class="card-text small text-white">{{ __('admin.weak') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small total">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
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
                <th>{{ __('admin.muhafez') }}</th>
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
                    <td>{{ $quraan->student->classes->first()->name ?? __('admin.not_assigned') }}</td>
                    <td>{{ $quraan->student->muhafez->name ?? __('admin.not_assigned') }}</td>
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
                            {{ $quraan->degree }} / 30
                        </span>
                    </td>
                    <td>{{ $quraan->notes }}</td>
                    <td>
                        {{-- <a href="{{ route('admin.quraans.show', $quraan) }}"
                            class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> {{__('admin.view')}}
                        </a> --}}
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
