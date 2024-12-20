<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\EditInfoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserInformationController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('user.info', compact('user'));
    }

    public function update(EditInfoRequest $request)
    {
        $attributes = $request->validated();

        try {
            Auth::user()->update($attributes);
            return redirect()->route('user.info')->with('success', 'Update successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to update information. Please try again.');
        }
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        $attributes = $request->validated();
        try {
            $user = Auth::user();
            $user->update([
                'password' => Hash::make($attributes['password'])
            ]);
            return redirect()
                ->route('user.info')
                ->with('success', 'Password updated successfully');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Failed to update password. Please try again.');
        }
    }
}
