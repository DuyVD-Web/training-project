<?php

namespace App\Enums;

enum UserRole : string
{
    const Admin = 'admin';
    const Manager = 'manager';
    const User = 'user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
