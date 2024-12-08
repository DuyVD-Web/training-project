<?php

namespace App\Exports\Sheets;

use App\Enums\UserRole;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AdminsSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return User::where('role', UserRole::Admin)
            ->select('name', 'email','role', 'phone_number','address')->get();
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
            'role',
            'phone_number',
            'address'
        ];
    }
}

