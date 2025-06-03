<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;

class StudentImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $student =  Student::create([
            'name' => $row['name'],
            'phone' => $row['phone'] ?? null,
            'muhafez_id' => $row['muhafez_id'] ?? null,
        ]);

        $student->classes()->attach($row['class_id'] ?? null);

        return $student;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            // 'class_id' => 'nullable|exists:school_classes,id',
            // 'muhafez_id' => 'nullable|exists:muhafezs,id',
        ];
    }
}
