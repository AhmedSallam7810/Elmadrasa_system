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
                    <h3 class="card-title mb-0 text-white">{{ $stats['present'] }}</h3>
                    <p class="card-text small">{{ __('admin.present') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small present">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg col-4 mb-4">
        <div class="card text-white bg-warning h-100 shadow rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0 text-dark">{{ $stats['late'] }}</h3>
                    <p class="card-text small text-dark">{{ __('admin.late') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-dark text-decoration-none small late">
                    {{ __('admin.show') }} <i class="fas fa-arrow-circle-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg col-4 mb-4">
        <div class="card text-white bg-danger h-100 shadow rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title mb-0 text-white">{{ $stats['absent'] }}</h3>
                    <p class="card-text small text-white">{{ __('admin.absent') }}</p>
                </div>
                <div class="display-5">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-end">
                <a href="#" class="text-white text-decoration-none small absent">
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
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.degree') }}</th>
                <th>{{ __('admin.notes') }}</th>
                <th>{{ __('admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->date->format('Y-m-d') }}</td>
                    <td>{{ $attendance->student->name }}</td>
                    <td>{{ $attendance->student->classes->first()->name ?? 'Not Assigned' }}</td>
                    <td>
                        <span class="badge badge-{{ $attendance->status_color }}">
                            {{__('admin.'.$attendance->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-info">
                            {{ $attendance->degree }} / 30
                        </span>
                    </td>
                    <td>{{ $attendance->notes }}</td>
                    <td>
                        {{-- <a href="{{ route('admin.attendances.show', $attendance) }}"
                            class="btn btn-info btn-sm">
                            <i class="fas fa-eye">{{__('admin.view')}}</i>
                        </a> --}}
                        <a href="{{ route('admin.attendances.edit', $attendance) }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-edit">{{__('admin.edit')}}</i>
                        </a>
                        <form action="{{ route('admin.attendances.destroy', $attendance) }}"
                            method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                                <i class="fas fa-trash">{{__('admin.delete')}}</i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('admin.no_attendance_records_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


