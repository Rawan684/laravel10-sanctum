<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
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
        $user->generateTwoFactorCode();
        $user->save();

        $token = $user->createToken('Api Token Of ' . $user->name)->plainTextToken;
        return $this->success([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function resendTwoFactorCode(Request $request)
    {

        $user = User::where('email', $request->email)->first();
        if (!$user->two_factor_code) {
            return response()->json(['error' => 'Two-factor authentication not enabled'], 400);
        }
        // Send 2FA code
        $user->generateTwoFactorCode();

        return response()->json(['message' => '2FA code resent successfully'], 200);
    }


    public function confirmTwoFactorCode(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->confirmTwoFactorCode($request->input('code'))) {
            return response()->json(['message' => '2Fa verified successfully']);
        }

        return response()->json(['error' => 'Invalid verification code'], 422);
    }

    public function refreshToken(Request $request)
    {
        $request->user()->tokens()->delete();
        $token = $request->user()->createToken('api', ['expires_at' => now()->addMinutes(20)]);
        return response()->json([
            'new_token' => $token->plainTextToken,
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
