<?php

namespace App\Enums;

enum UserRole : string
{
    const User = 'user';
    const Admin = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
