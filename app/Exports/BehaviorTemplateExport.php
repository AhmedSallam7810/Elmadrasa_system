<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BehaviorTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    protected $classId;
    protected $date;

    public function __construct($classId = null, $date = null)
    {
        $this->classId = $classId;
        $this->date = $date;
    }

    public function collection()
    {
        $query = Student::with(['classes'])
            ->whereDoesntHave('behaviors', function ($query) {
                if ($this->date) {
                    $query->whereDate('date', $this->date);
                }
            });

        if ($this->classId) {
            $query->whereHas('classes', function ($q) {
                $q->where('classes.id', $this->classId);
            });
        }

        return $query->orderBy('name')->get();
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
            'good',
            '10',
            '',
        ];
    }
}
