<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;


class ClassExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping
{
    private $row = 0;
    protected $classes;

    public function __construct($classes)
    {
        $this->classes = $classes;
    }

    public function collection()
    {
        return $this->classes;
    }
    public function headings(): array
    {
        return [
            'Stt',
            'Mã lớp',
            'Tên',
            'Mô tả',
            'Sinh viên',
            'Buổi học',
            'Chuyên cần',
            'Ngày tạo',
        ];
    }

    public function map($class): array
    {
        $this->row++;

        return [
            $this->row,
            $class->code,
            $class->name,
            $class->description,
            $class->countStudent(),
            $class->countLesson(),
            ($class->getAverageAttendance()??0)."%",
            $class->created_at,
        ];
    }
}
