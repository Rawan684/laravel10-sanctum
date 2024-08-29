<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\VerificationCodeGenerated;
use App\Models\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->generateVerificationCode();
        $user->save();

        $token = $user->createToken('Api Token Of ' . $user->name)->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function verifyEmailCode(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->verifyEmailCode($request->input('code'))) {
            return response()->json(['message' => 'Email verified successfully']);
        }

        return response()->json(['error' => 'Invalid verification code'], 422);
    }


    public function refreshToken(Request $request)
    {
        $token = $request->user()->token();
        $newToken = $token->refresh();
        return response()->json(['token' => $newToken]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
