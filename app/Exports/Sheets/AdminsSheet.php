<?php

namespace App\Exports\Sheets;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AdminsSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return User::where('role_id', Config::get('constant.admin'))
            ->select('name', 'email', 'phone_number','address')->get();
    }

    public function title(): string
    {
        return 'Admins';
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

