<?php

namespace App\Http\Resources;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allPermissions = Permission::all()
            ->filter(function ($permission) {
                if ($this->name === 'manager') {
                    return str_starts_with($permission->name, 'api.admin') || str_starts_with($permission->name, 'api.user.information');
                }

                // For regular users, only include api.user.* permissions
                return str_starts_with($permission->name, 'api.user');
            })
            ->values();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'allPermissions' => PermissionResource::collection($allPermissions),
            'permissions' => PermissionResource::collection($this->permissions),
        ];
    }
}
