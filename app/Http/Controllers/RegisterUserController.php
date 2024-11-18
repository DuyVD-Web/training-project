<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
                'role' => 'user'
            ]);

            DB::commit();
            return redirect()->route('login')->with('success', 'Registration successful!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Registration failed.');
        }
    }
}
