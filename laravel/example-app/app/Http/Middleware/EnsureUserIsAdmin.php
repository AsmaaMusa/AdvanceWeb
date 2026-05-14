<?php

namespace App\Http\Middleware;

use App\Models\AdminSession;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if (! $bearerToken) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $tokenHash = hash('sha256', $bearerToken);
        $adminSession = AdminSession::query()
            ->with('user')
            ->where('token_hash', $tokenHash)
            ->first();

        $user = $adminSession?->user;

        if (! $user) {
            $user = User::query()
                ->where('api_token', $tokenHash)
                ->first();
        }

        if (! $user) {
            return response()->json([
                'message' => 'Invalid or expired admin token.',
            ], 401);
        }

        if (! $user->isAdmin()) {
            return response()->json([
                'message' => 'Only admins can access this route.',
            ], 403);
        }

        if ($adminSession) {
            $adminSession->forceFill(['last_used_at' => now()])->save();
            $request->attributes->set('admin_session', $adminSession);
        }

        $request->attributes->set('auth_user', $user);
        $request->setUserResolver(fn (): User => $user);

        return $next($request);
    }
}
