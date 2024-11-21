<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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
        } catch (QueryException $e) {
            return redirect()->route('admin.users')->with('error', $e->getMessage());
        }
    }
}
