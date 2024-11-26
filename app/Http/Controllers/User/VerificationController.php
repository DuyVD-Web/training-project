<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request){
        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request){
        $request->fulfill();
        return redirect()->route('dashboard');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    }
}
