<?php

namespace App\Exports;

use App\Models\Classes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;


class StudentClassExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping, WithTitle
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
            'Vào lớp',
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
            $student->getJoinDate($this->classId),
            $student->classAttendRate($this->classId)."%",
        ];
    }

    public function title(): string
    {   
        $class = Classes::findOrFail($this->classId);
        return "DS sinh viên lớp $class->name";
    }
}
