<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(AuthRequest $request)
    {
        $attributes = $request->validated();

        if (! Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Credentials do not match, please try again.',
            ]);
        }
        try {
            DB::beginTransaction();
            History::create([
                'ip_address' => request()->ip(),
                'browser' => request()->header('User-Agent'),
                'user_id' => Auth::id(),
                'time' => now(),
                'type' => 'login'
            ]);
            DB::commit();
            request()->session()->regenerate();
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Login failed.');
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();
            History::create([
                'ip_address' => request()->ip(),
                'browser' => request()->header('User-Agent'),
                'user_id' => Auth::id(),
                'time' => now(),
                'type' => 'logout'
            ]);
            Auth::logout();
            DB::commit();
            return redirect()->route('home');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Logout failed.');
        }
    }
}
