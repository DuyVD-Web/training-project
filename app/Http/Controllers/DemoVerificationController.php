<?php

namespace App\Http\Controllers;


use App\Models\EmailVerification;
use App\Notifications\DemoVerifyEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoVerificationController
{
    public function notice()
    {
        return view('DemoVerification.verify');
    }

    public function verify($token)
    {
        $verification = EmailVerification::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return redirect()->route('verification.notice')
                ->with('error', 'Invalid or expired verification link.');
        }

        $user = $verification->user;

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'User not found.');
        }

        // Mark email as verified
        try {
            DB::beginTransaction();
            $user->email_verified_at = Carbon::now();
            $user->save();
            $verification->delete();
            DB::commit();
            return redirect()->route('dashboard')
                ->with('success', 'Email verified successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('verification.notice');
        }
    }

    public function sendVerification(Request $request)
    {
        $user = $request->user();

        try {
            DB::beginTransaction();
            EmailVerification::where('user_id', $user->id)->delete();
            $verification = EmailVerification::create([
                'user_id' => $user->id,
                'token' => Str::random(64),
                'expires_at' => Carbon::now()->addMinutes(5),
            ]);
            DB::commit();
            $user->notify(new DemoVerifyEmail($verification->token));

            return back()->with('success', 'Verification link sent!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong!');
        }
    }
}
