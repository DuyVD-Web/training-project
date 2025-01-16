<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\AccessType;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\History;
use App\Models\User;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(AuthRequest $request)
    {
        $attributes = $request->validated();

        if (!Auth::attempt($attributes)) {
            return $this->responseError([
                'message' => 'Invalid credentials',
            ], code: 403);
        }

        try {
            DB::beginTransaction();
            $user = new UserResource(User::where('email', $attributes['email'])->first());
            $userAgent = $request->header('User-Agent');
            $agent = new Agent();

            $browser = $agent->browser($userAgent);
            $platform = $agent->platform($userAgent);
            $device = $agent->device($userAgent) ?? 'Unknown';

            History::create([
                'ip_address' => $request->ip(),
                'browser' => $browser,
                'platform' => $platform,
                'device' => $device,
                'user_id' => $user->id,
                'time' => now(),
                'type' => AccessType::Login,
            ]);
            DB::commit();


            return $this->responseSuccess([
                'user' => $user,
                'permissions' => PermissionResource::collection($user->role->permissions),
                'token' => $user->createToken('API Token')->plainTextToken,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError('Something went wrong in the process.');
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
//
//            $verification = EmailVerification::updateOrCreate(['user_id' => $user->id],
//                ['token' => Str::random(40), 'expires_at' => Carbon::now()->addMinutes(5),]);
//
//            SendVerifyEmail::dispatch($user, $verification->token);

            DB::commit();
            return $this->responseSuccess();
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError([
                'message' => 'Registration failed',
            ]);
        }
    }

    public function logout()
    {
        $user = Auth::user();
        try {
            DB::beginTransaction();
            $userAgent = request()->header('User-Agent');
            $agent = new Agent();

            History::create([
                'ip_address' => request()->ip(),
                'browser' => $agent->browser($userAgent),
                'platform' => $agent->platform($userAgent),
                'device' => $agent->device($userAgent) ?? 'Unknown',
                'user_id' => $user->id,
                'time' => now(),
                'type' => AccessType::Logout,
            ]);
            $user->currentAccessToken()->delete();

            DB::commit();
            return $this->responseSuccess();
        } catch (Exception $e) {
            DB::rollBack();
            return $this->responseError('Logout process failed');
        }
    }
}
