<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ManagerSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return User::where('role_id', Config::get('constant.manager'))
            ->select('name', 'email', 'phone_number','address')->get();
    }

    public function title(): string
    {
        return 'Managers';
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'phone_number',
            'address'
        ];
    }
}
