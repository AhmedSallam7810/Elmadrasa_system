<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{ __('admin.id') }}</th>
                <th>{{ __('admin.name') }}</th>
                <th>{{ __('admin.phone') }}</th>
                <th>{{ __('admin.class') }}</th>
                <th>{{ __('admin.muhafez') }}</th>
                <th>{{ __('admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->phone ?? __('admin.not_available') }}</td>
                    <td>
                        @if($student->classes->isNotEmpty())
                            {{ $student->classes->pluck('name')->implode(', ') }}
                        @else
                            {{ __('admin.no_classes') }}
                        @endif
                    </td>
                    <td>
                        {{ $student->muhafez->name ?? __('admin.no_muhafez') }}
                    </td>
                    <td>
                        <a href="{{ route('admin.students.show', $student) }}"
                            class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> {{__('admin.view')}}
                        </a>
                        <a href="{{ route('admin.students.edit', $student) }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> {{__('admin.edit')}}
                        </a>
                        <form action="{{ route('admin.students.destroy', $student) }}"
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
                    <td colspan="7" class="text-center">{{ __('admin.no_students_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


