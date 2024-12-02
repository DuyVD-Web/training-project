<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }
    public function store(AuthRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $validated["name"],
                'email' => $validated["email"],
                'password' => Hash::make($validated["password"]),
                'role_id' => Role::where('name', UserRole::User)->first()->id,
            ]);
            DB::commit();
            event(new Registered($user));
            return redirect()->route('login')->with('success', 'Registration successful!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Registration failed.');
        }
    }
}
