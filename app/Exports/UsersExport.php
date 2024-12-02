<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersExport implements WithMultipleSheets
{
    /**
     * @return array
     */
    public function sheets(): array
    {
        return [
            new Sheets\UsersSheet(),
            new Sheets\AdminsSheet(),
        ];
    }
}
