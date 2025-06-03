<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $classId;

    public function __construct($classId)
    {
        $this->classId = $classId;
    }

    public function model(array $row)
    {
        $student = Student::create([
            'name' => $row['name'],
            'phone' => $row['phone'] ?? null,
        ]);

        if ($this->classId) {
            $student->classes()->attach($this->classId, [
                'enrolled_at' => now(),
                'is_active' => true
            ]);
        }

        return $student;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ];
    }
}
