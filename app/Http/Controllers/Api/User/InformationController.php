<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\EditInfoRequest;
use App\Http\Resources\UserResource;
use App\Models\ChangeEmailRequest as ChangeEmailModel;
use App\Notifications\ChangeEmailNotification;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InformationController
{
    use HttpResponses;

    public function getInformation()
    {
        $user = new UserResource(Auth::user());
        return $this->responseSuccess([
            'user' => $user
        ]);
    }

    public function update(EditInfoRequest $request)
    {
        $attributes = $request->validated();

        try {
            $user = Auth::user();
            $user->update($attributes);
            return $this->responseSuccess([
                'user' => new UserResource($user)
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->responseError('There was error while updating information', 500);
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
            return $this->responseSuccess();

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->responseError('There was error while changing your password', 500);
        }
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
            return $this->responseSuccess();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseError('There was error while updating email', 500);
        }
    }

    public function verifyChangeEmail($token)
    {
        $changeRequest = ChangeEmailModel::where('token', $token)
            ->where('expires_at', '>', Carbon::now())->first();

        if (!$changeRequest) {
            return $this->responseError('Invalid or expired verification link!', 500);
        }
        $user = $changeRequest->user;

        if (!$user) {
            return $this->responseError('User not found.', 500);
        }

        try {
            DB::beginTransaction();
            $user->email = $changeRequest->new_email;
            $user->save();
            $changeRequest->delete();
            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseError('Something went wrong.', 500);
        }
    }
}
