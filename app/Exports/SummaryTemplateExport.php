<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SummaryTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'student_id',
            'name',
            'class',
            'status',
            'degree',
            'notes'
        ];
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->name,
            $student->classes->first() ? $student->classes->first()->id : '',
            'good', // default status
            '10', // default degree
            '' // empty notes
        ];
    }
}
