<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\ChangeAvatar;
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
use Illuminate\Support\Facades\Storage;
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
            return $this->responseError('There was error while updating information');
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
            return $this->responseError('There was error while changing your password');
        }
    }

    public function updateAvatar (ChangeAvatar $request)
    {
        $request->validated();
        $user = $request->user();
        $old_avatar = $user->avatar;

//        $path = $request->file('img')->store('avatars', 'public');
        $path = Storage::disk('public')->putFile('avatars', $request->file('img'));
        try {
            $user->update([
                'avatar' => $path
            ]);
            if ($old_avatar) {
                Storage::disk('public')->delete($old_avatar);  // Use public disk to delete
            }
            return $this->responseSuccess([
                'avatar' =>  asset(Storage::url($path))
            ]);
        } catch (\Exception $th) {
            return $this->responseError('There was error while updating your avatar');
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
            return $this->responseError('There was error while updating email');
        }
    }

    public function verifyChangeEmail($token)
    {
        $changeRequest = ChangeEmailModel::where('token', $token)
            ->where('expires_at', '>', Carbon::now())->first();

        if (!$changeRequest) {
            return $this->responseError('Invalid or expired verification link!');
        }
        $user = $changeRequest->user;

        if (!$user) {
            return $this->responseError('User not found.');
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
            return $this->responseError('Something went wrong.');
        }
    }
}
