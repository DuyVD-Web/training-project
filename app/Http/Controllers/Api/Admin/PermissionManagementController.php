<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;

class PermissionManagementController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $roles = Role::where('name', '!=', UserRole::Admin)
            ->with('permissions')
            ->get();

        return $this->responseSuccess([
            'roles' => RoleResource::collection($roles),
        ]);
    }

    public function update(Request $request)
    {
        try {
            $role = Role::findOrFail($request->role_id);
            $role->permissions()->sync($request->input('permissions', []));
            return $this->responseSuccess([
                'role' => $role->where('id', $request->role_id)->with('permissions')->get()
            ]);
        } catch (Exception $e) {
            return $this->responseError("There was an error when processing your request.");
        }
    }
}
