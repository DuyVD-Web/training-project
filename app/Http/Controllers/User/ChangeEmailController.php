<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeEmailRequest;
use App\Models\ChangeEmailRequest as ChangeEmailModel;
use App\Notifications\ChangeEmailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChangeEmailController extends Controller
{
    public function index()
    {
        return view('user.change-email');
    }

    public function sendChangeEmail(ChangeEmailRequest $request)
    {
        $request->validated();
        $user = $request->user();

        try {
            DB::beginTransaction();
            $changeRequest = ChangeEmailModel::updateOrCreate(['user_id' => $user->id], [
                'new_email' => $request->email,
                'token' => Str::random(64),
                'expires_at' => Carbon::now()->addMinutes(10),
                ]);
            DB::commit();
            $user->notify(new ChangeEmailNotification($changeRequest->token, $changeRequest->new_email));
            return back()->with('success', 'Request link sent!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function verifyChangeEmail($token)
    {
        $changeRequest = ChangeEmailModel::where('token', $token)
            ->where('expires_at', '>', Carbon::now())->first();

        if (!$changeRequest) {
            return redirect()->route('change-email')->with('error', 'Invalid or expired verification link!');
        }

        $user = $changeRequest->user;

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        try {
            DB::beginTransaction();
            $user->email = $changeRequest->new_email;
            $user->save();
            $changeRequest->delete();
            DB::commit();
            return redirect()->route('user.info')->with('success', 'New email updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('change-email')->with('error', 'Something went wrong!');
        }
    }
}
