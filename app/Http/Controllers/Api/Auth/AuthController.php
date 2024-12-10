<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\AccessType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\History;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;

class AuthController extends Controller
{
    use HttpResponses;
    public function login(AuthRequest $request)
    {
        $attributes = $request->validated();

        if (!Auth::attempt($attributes)) {
            return $this->responseError([
                'message' => 'Invalid credentials',
                'errors' => "There's an error occurred ..."
            ], 403);
        }

        try {
            DB::beginTransaction();
            $user = User::where('email', $attributes['email'])->first();
            History::create([
                'ip_address' => request()->ip(),
                'browser' =>  Agent::browser(),
                'platform' => Agent::platform(),
                'device' => Agent::device(),
                'user_id' => $user->id,
                'time' => now(),
                'type' => AccessType::Login,
            ]);
            DB::commit();
            return $this->responseSuccess([
                'user' => $user,
                'token' => $user->createToken('API Token')->plainTextToken,
                'message' => 'Logged in successfully',
                'status' => "Logged in successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseError([
                'message' => 'Something went wrong',
                'status' => "There's an error occurred ..."
            ]);
        }
    }

    public function register(AuthRequest $request)
    {
        $attributes = $request->validated();

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $attributes["name"],
                'email' => $attributes["email"],
                'password' => Hash::make($attributes["password"]),
                'role_id' => Config::get("constant.user_id"),
            ]);
            DB::commit();
//            TODO: Queue this up
//            event(new Registered($user));
            return $this->responseSuccess([
                'message' => 'Registered successfully',
                'status' => "Successfully"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseError([
                'message' => 'Registered failed',
                'status' => "Failed"
            ]);
        }
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();



        return $this->responseSuccess([
            'message' => 'Logged out successfully',
            'status' => "Successfully"
        ]);
    }
}
