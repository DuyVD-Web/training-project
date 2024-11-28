<?php

namespace App\Enums;

enum ImportStatus :string
{
    const Done = 'done';
    const Failed = 'failed';
    const Pending = 'pending';
}
