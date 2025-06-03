<?php

namespace App\Imports;

use App\Models\Behavior;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Carbon;

class BehaviorImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function model(array $row)
    {


        return new Behavior([
            'student_id' => $row['student_id'],
            'date' => $this->date,
            'status' => strtolower($row['status']),
            'degree' => $row['degree'],
            'notes' => $row['notes'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            // 'student_id' => 'required|exists:students,id',
            // 'status' => 'required|in:good,average,weak',
            // 'degree' => 'required|numeric|min:0|max:10',
            // 'notes' => 'nullable|string|max:255',
        ];
    }
}
