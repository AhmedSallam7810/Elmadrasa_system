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
                    <i class="fas fa-clipboard-list"></i>
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
                    <p class="card-text small text-white">{{ __('admin.good_behavior') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-smile"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small good">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg col-4 mb-4">
        <div class="card text-white bg-warning h-100 shadow rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0 text-dark">{{ $stats['average'] }}</h3>
                    <p class="card-text small text-dark">{{ __('admin.average_behavior') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-meh"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-dark text-decoration-none small average">
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
                    <p class="card-text small text-white">{{ __('admin.weak_behavior') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-frown"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small weak">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Behavior Records Table -->
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('admin.student_name') }}</th>
                <th>{{ __('admin.class') }}</th>
                <th>{{ __('admin.date') }}</th>
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.degree') }}</th>
                <th>{{ __('admin.notes') }}</th>
                <th>{{ __('admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($behaviors as $behavior)
                <tr>
                    <td>{{ $behavior->student->name }}</td>
                    <td>{{ $behavior->student->classes->pluck('name')->implode(', ') }}</td>
                    <td>{{ $behavior->date->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge badge-{{ $behavior->status === 'good' ? 'success' : ($behavior->status === 'average' ? 'warning' : 'danger') }}">
                            {{ __('admin.' . $behavior->status) }}
                        </span>
                    </td>
                    <td>{{ $behavior->degree }}</td>
                    <td>{{ $behavior->notes }}</td>
                    <td>
                        {{-- <a href="{{ route('admin.behaviors.show', $behavior) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> {{ __('admin.view') }}
                        </a> --}}
                        <a href="{{ route('admin.behaviors.edit', $behavior) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                        </a>
                        <form action="{{ route('admin.behaviors.destroy', $behavior) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('admin.are_you_sure') }}')">
                                <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('admin.no_records_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
.small-box {
    position: relative;
    display: block;
    margin-bottom: 20px;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: 0.25rem;
}

.small-box > .inner {
    padding: 10px;
}

.small-box h3 {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    white-space: nowrap;
    padding: 0;
}

.small-box p {
    font-size: 1rem;
    margin: 0;
}

.small-box .icon {
    color: rgba(0,0,0,.15);
    z-index: 0;
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 70px;
}

.small-box .icon i {
    font-size: 70px;
}

.bg-info {
    background-color: #17a2b8 !important;
    color: #fff !important;
}

.bg-success {
    background-color: #28a745 !important;
    color: #fff !important;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #1f2d3d !important;
}

.bg-danger {
    background-color: #dc3545 !important;
    color: #fff !important;
}
</style>
