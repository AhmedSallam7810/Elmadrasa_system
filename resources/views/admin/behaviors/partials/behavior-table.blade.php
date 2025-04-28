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
            @forelse($behaviors as $behavior)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($behavior->date)->format('Y-m-d') }}</td>
                    <td>{{ $behavior->student->name }}</td>
                    <td>{{ $behavior->student->classes->first()->name ?? __('admin.not_assigned') }}</td>
                    <td>
                        @if($behavior->status === 'good')<span class="badge badge-success">{{ __('admin.good') }}</span>
                        @elseif($behavior->status === 'average')<span class="badge badge-warning">{{ __('admin.average') }}</span>
                        @else<span class="badge badge-danger">{{ __('admin.weak') }}</span>@endif
                    </td>
                    <td><span class="badge badge-info">{{ $behavior->degree }} / 10</span></td>
                    <td>{{ $behavior->notes }}</td>
                    <td>
                        <a href="{{ route('admin.behaviors.show', $behavior) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> {{ __('admin.view') }}</a>
                        <a href="{{ route('admin.behaviors.edit', $behavior) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> {{ __('admin.edit') }}</a>
                        <form action="{{ route('admin.behaviors.destroy', $behavior) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('admin.confirm_delete') }}')"><i class="fas fa-trash"></i> {{ __('admin.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">{{ __('admin.no_behavior_records_found') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
