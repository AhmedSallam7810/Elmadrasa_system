<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsWithoutAttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;
    protected $classId;

    public function __construct($students, $classId = null)
    {
        $this->students = $students;
        $this->classId = $classId;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'name',
            'class',
            'degree',
            'status',
            'notes'
        ];
    }

    public function map($student): array
    {
        return [
            $student->name,
            $this->classId ? $student->classes->where('id', $this->classId)->first()->name ?? '' : '',
            '', // Empty degree
            '', // Empty status
            '', // Empty notes
        ];
    }
}
