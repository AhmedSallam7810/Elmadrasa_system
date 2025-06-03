<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $date;

    public function __construct($date)
    {
        // dd($date);
        $this->date = $date;
    }

    public function model(array $row)
    {
        // dd($row);
        return new Attendance([
            'student_id' => $row['student_id'],
            'date' => $this->date,
            'status' => $row['status'],
            'degree' => $row['degree'],
            'notes' => $row['notes'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            // 'name' => 'required|exists:students,id',
            // 'class' => 'required|exists:classes,id',
            // 'status' => 'required|in:present,absent,late,excused',
            // 'degree' => 'required|numeric|min:0|max:30',
            // 'notes' => 'nullable|string|max:255'
        ];
    }
}
