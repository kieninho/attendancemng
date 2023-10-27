<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class StudentExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping, WithTitle
{
    private $row = 0;
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
            'Stt',
            'Mã SV',
            'Tên',
            'email',
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
            $student->attendRate()."%",
        ];
    }

    public function title(): string
    {
        return 'Danh sách học sinh';
    }
}
