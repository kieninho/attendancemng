<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class StudentDetailExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping, WithTitle
{
    private $row = 0;
    protected $classes;
    protected $student;

    public function __construct($student,$classes)
    {
        $this->classes = $classes;
        $this->student = $student;
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
            'Số buổi',
            'Chuyên cần',
        ];
    }

    public function map($class): array
    {
        $this->row++;
        $all =  $class->countLessonWithStudentId($this->student->id);
        $attend = $class->countAttendWithStudentId($this->student->id);
        if($all == 0){
            $rate = 0;
        }
        else 
        $rate = round($attend/$all*100);

        return [
            $this->row,
            $class->code,
            $class->name,
            $all =  $class->countLessonWithStudentId($this->student->id)??"0",
            $rate."%",
        ];
    }

    public function title(): string
    {
        return "Chi tiết học sinh ".$this->student->code. "-".$this->student->name;
    }
}
