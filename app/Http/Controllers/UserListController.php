<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserListController extends Controller
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
            return redirect()->route('admin.users');
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
            User::create([
                'name' => $validated["name"],
                'email' => $validated["email"],
                'password' => Hash::make($validated["password"]),
                'role' => $validated["role"],
                'phone_number' => $validated["phone_number"],
                'address' => $validated["address"],
            ]);
            return redirect()->route('admin.users');
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
            User::where('id', $user->id)->update([
                'name' => $validated["name"],
                'phone_number' => $validated["phone_number"],
                'address' => $validated["address"],
                'role' => $validated["role"],
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.users.showEdit', $user)->with('error', $e->getMessage());
        }
        return redirect()->route('admin.users.showEdit', $user);
    }
}
