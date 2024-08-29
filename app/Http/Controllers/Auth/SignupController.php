<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Events\UserRegistered;
use App\Traits\HttpResponses;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class SignupController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());
        $user = new User();
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->name = $request->input('name');
        $profilePhoto = $request->file('profile_photo');
        $certificate = $request->file('certificate');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Save the uploaded files to the Filesystem storage
        $profilePhotoPath = Storage::disk('public')->put('profile-photos', $profilePhoto);
        $certificatePath = Storage::disk('public')->put('certificates', $certificate);

        // Update the user model with the saved file paths
        $user->profile_photo = $profilePhotoPath;
        $user->certificate = $certificatePath;

        $token = $user->createToken('Api Token Of ' . $user->name)->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}
