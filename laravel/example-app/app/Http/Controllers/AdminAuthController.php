<?php

namespace App\Http\Controllers;
use App\Models\AdminSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are invalid.'],
            ]);
        }

        if (! $user->isAdmin()) {
            return response()->json([
                'message' => 'This account is not allowed to access the admin area.',
            ], 403);
        }

        $plainTextToken = Str::random(60);

        AdminSession::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plainTextToken),
            'last_used_at' => now(),
        ]);

        $user->forceFill(['last_admin_login_at' => now()])->save();

        return response()->json([
            'message' => 'Admin login successful.',
            'token' => $plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'last_login_at' => optional($user->last_admin_login_at)->toISOString(),
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('auth_user');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->attributes->get('auth_user');
        $adminSession = $request->attributes->get('admin_session');

        if ($adminSession) {
            $adminSession->delete();
        } else {
            $user->forceFill([
                'api_token' => null,
            ])->save();
        }

        return response()->json([
            'message' => 'Admin logout successful.',
        ]);
    }
}
