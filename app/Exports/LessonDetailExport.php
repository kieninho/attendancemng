<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;


class LessonDetailExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping, WithTitle
{
    private $row = 0;
    protected $students;
    protected $lesson;

    public function __construct($students, $lesson)
    {
        $this->students = $students;
        $this->lesson = $lesson;
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
            'Tình trạng',
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
            $student->attendString($this->lesson->id),
        ];
    }

    public function title(): string
    {
        return "Điểm danh lớp ".$this->lesson->classes->name." - ". $this->lesson->name;
    }
}
