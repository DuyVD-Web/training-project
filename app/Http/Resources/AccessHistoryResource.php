<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'browser' => $this->browser,
            'device' => $this->device,
            'ipAddress' => $this->ip_address,
            'platform' => $this->platform,
            'type' => $this->type,
            'time' =>$this->time
        ];
    }
}
