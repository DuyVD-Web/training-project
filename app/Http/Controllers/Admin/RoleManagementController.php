<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleManagementController extends Controller
{
    public function index()
    {
        $roles = Role::where('name' , '!=', 'admin')->with('permissions')->get();
        $allPermissions = Permission::all();

        return view('admin.role-management', compact('roles', 'allPermissions'));
    }

    public function update(Request $request){
        try {
            $role = Role::findOrFail($request->role_id);
            $role->permissions()->sync($request->input('permissions', []));
            return redirect()->back()->with('success', 'Permissions updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Permissions update failed');
        }

    }
}
