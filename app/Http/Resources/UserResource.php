<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role->name,
            'phoneNumber' => $this->phone_number,
            'address'  => $this->address,
            'avatar' => $this->avatar ? asset(Storage::url($this->avatar)) : asset(Storage::url( Config::get('constant.default_avatar'))),
        ];
    }
}
