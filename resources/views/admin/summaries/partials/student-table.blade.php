@if($students->isEmpty())
  <div class="alert alert-info">{{ __('admin.no_students_found_without_records') }}</div>
@else
<div class="table-responsive">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th><input type="checkbox" id="select-all"></th>
        <th>{{ __('admin.student_name') }}</th>
        <th>{{ __('admin.class') }}</th>
        <th>{{ __('admin.status') }}</th>
        <th>{{ __('admin.degree') }}</th>
        <th>{{ __('admin.notes') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $student)
      <tr>
        <td><input type="checkbox" class="student-checkbox" name="student_ids[]" value="{{ $student->id }}" checked></td>
        <td>{{ $student->name }}</td>
        <td>{{ $student->classes->pluck('name')->implode(', ') }}</td>
        <td>
          <select name="status[{{ $student->id }}]" class="form-control status-select" data-student-id="{{ $student->id }}">
            <option value="good">{{ __('admin.good') }}</option>
            <option value="average">{{ __('admin.average') }}</option>
            <option value="weak">{{ __('admin.weak') }}</option>
          </select>
        </td>
        <td><input type="number" name="degree[{{ $student->id }}]" class="form-control degree-input" value="10" min="0" max="10" step="0.5" data-student-id="{{ $student->id }}"></td>
        <td><input type="text" name="notes[{{ $student->id }}]" class="form-control"></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-3">
  <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
  <a href="{{ route('admin.summaries.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
</div>
@endif
