<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;


class StudentClassExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping
{
    private $row = 0;
    protected $students;
    private $classId;

    public function __construct($students,$classId)
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
            'Stt',
            'Mã SV',
            'Tên',
            'Email',
            'Ngày sinh',
            'Chuyên cần',
        ];
    }

    public function map($student): array
    {
        $this->row++;

        return [
            $this->row,
            $student->code,
            $student->name,
            $student->email,
            $student->birthday,
            $student->classAttendRate($this->classId)."%",
        ];
    }
}
