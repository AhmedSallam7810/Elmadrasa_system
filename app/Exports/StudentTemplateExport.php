<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return collect([
            (object)[
                'name' => '',
                'phone' => '',
                'class_id' => '',
                'muhafez_id' => '',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'name',
            'phone',
            'class_id',
            'muhafez_id',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->phone,
            $row->class_id,
            $row->muhafez_id,
        ];
    }
}
