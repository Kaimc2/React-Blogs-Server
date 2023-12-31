<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthenticationController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name,except,id'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('ACCESS_TOKEN')->plainTextToken;

        event(new Registered($user));

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            "message" => "Register successfully",
        ])->withCookie("token", $token);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(["message" => "Email or password is incorrect"], 401);
        }

        $token = $user->createToken('ACCESS_TOKEN')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
            "message" => "Login successfully"
        ])->withCookie("token", $token);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(["message" => "Logged out successfully"]);
    }

    public function user(Request $request)
    {
        if (!$request->user()) {
            return response()->json(["message" => "Unauthenticated"], 401);
        }

        return new UserResource($request->user());
    }
}
