<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\ImportStatus as Status;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UsersImportRequest;
use App\Http\Resources\UserResource;
use App\Jobs\CustomImportProcess;
use App\Models\ImportStatus;
use App\Models\Role;
use App\Models\User;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UsersManagementController extends Controller
{
    use HttpResponses;

    public function getUsers(Request $request)
    {
        $query = User::query()->where('id', '!=', Auth::id());
        if (Auth::user()->role_id !== Config::get('constant.admin_id')) {
            $query->whereNotIn('role_id', [Config::get('constant.admin_id')]);
        }

        if ($request->has('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->has('roles')) {
            $roles = Role::whereIn('name', $request->roles)->get();
            $query->whereIn('role_id', $roles->pluck('id'));
        }

        if ($request->has('verified')) {
            $query->whereNotNull('email_verified_at');
        }

        $field = $request->input('field', 'name');
        $sort = $request->input('sort', 'asc');

        if ($field === 'role') {
            $query->join('roles', 'users.role_id', '=', 'roles.id')
                ->orderBy('roles.name', $sort)
                ->select('users.*');
        } else if ($field === 'verifiedAt') {
            $query->orderBy("email_verified_at", $sort);
        } else {
            $query->orderBy($field, $sort);
        }


        $pageSize = $request->pageSize ? $request->pageSize : 5;

        $users = $query->paginate($pageSize);
        return $this->sendPaginateResponse($users,[
            'users' => UserResource::collection($users),
        ]);
    }

    public function delete($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();
            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError("There was an error occurred in the process");
        }
    }

    public function get($userId)
    {
        try {
            $user = User::findOrFail($userId);
            return $this->responseSuccess(['user' => new UserResource($user)]);
        } catch (Exception $e) {
            return $this->responseError("There was an error occurred in the process");
        }
    }

    public function update($userId, EditUserRequest $request)
    {
        $validated = $request->validated();
        try {
            $roleMapping = [
                'admin' => Config::get('constant.admin_id'),
                'manager' => Config::get('constant.manager_id'),
                'user' => Config::get('constant.user_id')
            ];

            $validated['role_id'] = $roleMapping[$validated['role']] ?? null;
            if ($validated['role_id'] === null) {
                throw new Exception("Invalid role selected");
            }
            unset($validated['role']); // Better than setting to null
            $user = User::findOrFail($userId);
            $user->update($validated);

            return $this->responseSuccess([
                'user' => new UserResource($user),
            ]);
        } catch (Exception $e) {
            return $this->responseError("There was an error occurred in the process");
        }
    }

    public function import(UsersImportRequest $request)
    {
        try {
            $request->validated();
            $path = $request->file('file')->store('imports');

            $import = ImportStatus::create([
                'status' => Status::Pending,
                'user_id' => Auth::id(),
            ]);
            CustomImportProcess::dispatch($path, $import->id);
            return $this->responseSuccess(code: 202);
        } catch (Exception $e) {
            return $this->responseError("There was an error occurred in the process", [
                'import' => "Import failed",
            ]);
        }
    }

    public function create(CreateUserRequest $request)
    {
        $validated = $request->validated();
        try {
            $roleMapping = [
                'admin' => Config::get('constant.admin_id'),
                'manager' => Config::get('constant.manager_id'),
                'user' => Config::get('constant.user_id')
            ];

            $validated['role_id'] = $roleMapping[$validated['role']] ?? null;
            if ($validated['role_id'] === null) {
                throw new Exception("Invalid role selected");
            }
            unset($validated['role']);

            if ($request->hasFile('avatar')) {
                $path = Storage::disk('public')->putFile('avatars', $request->file('avatar'));
                $validated['avatar'] = $path;
            }

            User::create($validated);
            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError("There was an error occurred in the process");
        }
    }

    public function export()
    {
        try {
            $filename = 'user_list_' . Carbon::now()->format('Ymd') . '.xlsx';
            return Excel::download(new UsersExport, $filename);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Export failed',
            ]);
        }
    }
}
