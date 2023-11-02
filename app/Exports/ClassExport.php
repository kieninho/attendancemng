<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;


class ClassExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping, WithTitle
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
            'Giáo viên',
            'Bài học',
            'Chuyên cần',
            'Khai giảng',
            'Kết thúc',
            'Trạng thái'
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
            $class->getTeachersStringByClass(),
            $class->countLesson(),
            ($class->getAverageAttendance())."%",
            $class->startDay(),
            $class->endDay(),
            $class->getStatus()
        ];
    }

    public function title(): string
    {
        return 'Danh sách lớp học';
    }
}
