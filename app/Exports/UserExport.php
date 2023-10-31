<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class UserExport implements FromCollection, WithHeadings, ShouldAutoSize,WithMapping, WithTitle
{
    private $row = 0;
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'Stt',
            'Tên',
            'Email',
            'Ngày tạo',
        ];
    }

    public function map($user): array
    {
        return [
            $this->row,
            $user->name,
            $user->email,
            $user->created_at,
        ];
    }

    public function title(): string
    {
        return 'Danh sách quản trị viên';
    }
}
