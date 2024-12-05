<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AccessType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Facades\Agent;

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
                'browser' =>  Agent::browser(),
                'platform' => Agent::platform(),
                'device' => Agent::device(),
                'user_id' => Auth::id(),
                'time' => now(),
                'type' => AccessType::Login,
            ]);
            DB::commit();
            request()->session()->regenerate();
            $userRole = Auth::user()->role->name;
            if ($userRole == UserRole::Admin || $userRole == UserRole::Manager) {
                return redirect()->route('admin.users');
            }
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
                'browser' =>  Agent::browser(),
                'platform' => Agent::platform(),
                'device' => Agent::device(),
                'user_id' => Auth::id(),
                'time' => now(),
                'type' => AccessType::Logout,
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
