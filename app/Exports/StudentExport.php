<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Style;

class StudentExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping
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

    // Đoạn này để lại để xử lý sau
    // public function registerEvents(): array
    // {
    //     return [
    //         BeforeSheet::class => function(BeforeSheet $event) {
    //             $event->sheet->mergeCells('A1:D1'); // Merge ô từ A1 đến D1
    //             $event->sheet->getStyle('A1')->applyAlignment([
    //                 'horizontal' => Alignment::HORIZONTAL_CENTER, // Căn giữa ô
    //                 'vertical' => Alignment::VERTICAL_CENTER,
    //             ]);
    //             $event->sheet->getStyle('A1')->getFont()->setBold(true); // Đặt font chữ in đậm
    //             $event->sheet->setCellValue('A1', 'Danh sách học sinh'); // Đặt nội dung cho ô
    //         },
    //         AfterSheet::class => function(AfterSheet $event) {
    //             $event->sheet->getStyle('F')->applyAlignment([
    //                 'horizontal' => Alignment::HORIZONTAL_CENTER, // căn giữa cột 'F    '
    //             ]);

    //         },
    //     ];
    // }
}
