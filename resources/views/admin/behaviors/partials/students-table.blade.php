<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="select-all">
                        <label class="custom-control-label" for="select-all" ></label>
                    </div>
                </th>
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
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input student-checkbox"
                                id="student_{{ $student->id }}"
                                name="student_ids[]"
                                value="{{ $student->id }}"
                                >
                            <label class="custom-control-label" for="student_{{ $student->id }}"></label>
                        </div>
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->classes->pluck('name')->implode(', ') }}</td>
                    <td>
                        <select class="form-control status-select @error('status.'.$student->id) is-invalid @enderror"
                            name="status[{{ $student->id }}]"
                            data-student-id="{{ $student->id }}">
                            <option value="good">{{ __('admin.good') }}</option>
                            <option value="average">{{ __('admin.average') }}</option>
                            <option value="weak">{{ __('admin.weak') }}</option>
                        </select>
                        @error('status.'.$student->id)
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                    <td>
                        <input type="number" class="form-control degree-input @error('degree.'.$student->id) is-invalid @enderror"
                            name="degree[{{ $student->id }}]"
                            value="10"
                            min="0"
                            max="30"
                            step="0.5"
                            data-student-id="{{ $student->id }}">
                        @error('degree.'.$student->id)
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                    <td>
                        <input type="text"
                            class="form-control @error('notes.'.$student->id) is-invalid @enderror"
                            name="notes[{{ $student->id }}]"
                            placeholder="{{ __('admin.optional_notes') }}">
                        @error('notes.'.$student->id)
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
