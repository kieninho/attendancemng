<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class TeacherExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping, WithTitle
{
    private $row = 0;
    protected $teachers;

    public function __construct($teachers)
    {
        $this->teachers = $teachers;
    }

    public function collection()
    {
        return $this->teachers;
    }

    public function headings(): array
    {
        return [
            'Stt',
            'Tên',
            'email',
            'phone',
            'Ngày sinh',
            'Số lớp dạy',
        ];
    }

    public function map($teacher): array
    {
        $this->row++;
        $countClass = $teacher->countClasses();
        if(!$countClass){
            $countClass = "0";
        }

        return [
            $this->row,
            $teacher->name,
            $teacher->email,
            $teacher->phone,
            $teacher->birthday,
            $countClass,
        ];
    }

    public function title(): string
    {
        return 'Danh sách giáo viên';
    }
}
