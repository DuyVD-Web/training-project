<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportStatus extends Model
{
    protected $fillable = [
        'status',
        'message',
        'user_id'
    ];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

}
