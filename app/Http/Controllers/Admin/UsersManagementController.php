<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\UsersImportRequest;
use App\Imports\UsersImport;
use App\Jobs\ProcessImportUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;


class UsersManagementController extends Controller
{
    public function index(Request $request)
    {

        $query = User::query();

        if ($request->has('search_query')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%'. $request->search_query .'%')
                    ->orWhere('email', 'LIKE', '%'. $request->search_query .'%');
            });
        }

        if ($request->has('roles')) {
            $query->whereIn('role', $request->roles);
        }

        if ($request->has('verified')) {
            $query->whereNotNull('email_verified_at');
        }

        $field = $request->input('field', 'name');
        $sort = $request->input('sort', 'asc');
        $search = $request->input('search_query', '');
        $query->orderBy($field, $sort);

        $users = $query->paginate(6);
        return view('admin.user-list', [
            'users' => $users,
            'field' => $field,
            'sort' => $sort,
            'search' => $search,
        ]);
    }

    public function delete(User $user){
        try {
            $user->delete();
            return redirect()->back()->with('success', 'User has been deleted');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', $e->getMessage());
        }
    }

    public function showCreateForm(){
        return view('admin.add-user');
    }

    public function create(CreateUserRequest $request){
        $validated = $request->validated();
        try {
            User::create($validated);
            return redirect()->route('admin.users')->with('success', 'User has been created');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.showCreateForm')->with('error', $e->getMessage());
        }
    }

    public function showEdit(User $user){
        return view('admin.edit-user', ['user' => $user]);
    }

    public function update(EditUserRequest $request, User $user)
    {
        $validated = $request->validated();
        try {
            User::where('id', $user->id)->update($validated);
            return redirect()->route('admin.users.showEdit', $user)->with('success', 'User has been updated');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.showEdit', $user)->with('error', $e->getMessage());
        }
    }

    public function import(UsersImportRequest $request)
    {
        try {
            $request->validated();
            $path = $request->file('file')->store('imports');

            ProcessImportUsers::dispatch($path);

            return redirect()->route('admin.users')
                ->with('success', 'Importing users.');
        } catch (\Exception) {
            return redirect()->route('admin.users')
                ->with('error', 'Import failed.');
        }

    }
}
