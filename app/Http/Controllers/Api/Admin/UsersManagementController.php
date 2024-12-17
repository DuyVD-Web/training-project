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
use Maatwebsite\Excel\Facades\Excel;

class UsersManagementController extends Controller
{
    use HttpResponses;

    public function getUsers(Request $request)
    {
        $query = User::query();

        if ($request->has('search_query')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search_query . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search_query . '%');
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
        } else {
            $query->orderBy($field, $sort);
        }

        $users = $query->paginate(6);
        return $this->responseSuccess([
            'users' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
                'links' => [
                    'first' => $users->url(1),
                    'last' => $users->url($users->lastPage()),
                    'prev' => $users->previousPageUrl(),
                    'next' => $users->nextPageUrl(),
                ]
            ]
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
            $user = User::where('id', $userId)->update($validated);
            return $this->responseSuccess([
                'user' => new UserResource(User::where('id', $userId)->first()),
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
