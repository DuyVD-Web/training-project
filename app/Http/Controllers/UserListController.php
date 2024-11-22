<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserListController extends Controller
{
    public function index(Request $request)
    {
        $field = $request->query('field') ? $request->query('field') : 'role';

        $sort = $request->query('sort') ? $request->query('sort') : 'asc';

        $users = User::query()
            ->orderBy($field, $sort);
        return view('admin.user-list', ['users' => $users->paginate(6),
            'field' => $field,
            'sort' => $sort,]);
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
}
