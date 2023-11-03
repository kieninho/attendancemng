<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ClassLessonExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping, WithTitle
{
    private $row = 0;
    protected $class;
    protected $lessons;

    public function __construct($class,$lessons)
    {
        $this->class = $class;
        $this->lessons = $lessons;
    }

    public function collection()
    {
        return $this->lessons;
    }

    public function headings(): array
    {
        return [
            'Stt',
            'Tên',
            'Mô tả',
            'Thời gian',
            'GV',
            'Chuyên cần',
        ];
    }

    public function map($lesson): array
    {
        $this->row++;
        $teacherString = "";
        foreach($lesson->teachers as $teacher){
            $teacherString = $teacherString . $teacher->name . ", ";
        }
        $teacherString = substr($teacherString, 0, strlen($teacherString) - 2);

        return [
            $this->row,
            $lesson->name,
            $lesson->description,
            $lesson->getStartAndEnd()??"0",
            $teacherString,
            $lesson->getAttendRate()."%",
        ];
    }

    public function title(): string
    {
        return "Buổi học lớp " . $this->class->code . " - " . $this->class->name;
    }
}
