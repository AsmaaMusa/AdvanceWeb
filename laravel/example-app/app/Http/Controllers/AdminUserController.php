<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));
        $status = strtolower(trim((string) $request->query('status', 'all')));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nestedQuery) use ($search): void {
                    $nestedQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status !== 'all', function ($query) use ($status): void {
                if ($status === 'active') {
                    $query->where('is_active', true);
                }

                if ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (User $user): array => $this->transformUser($user))
            ->values();

        return response()->json([
            'users' => $users,
            'stats' => [
                'total' => $users->count(),
                'active' => $users->where('is_active', true)->count(),
                'inactive' => $users->where('is_active', false)->count(),
                'admins' => $users->where('role', User::ROLE_ADMIN)->count(),
                'credits_total' => $users->sum('credits'),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $platformDefaults = AdminSettingsController::getPlatformDefaults();
        $passwordRule = AdminSettingsController::requireStrongPasswords()
            ? Password::min(8)->mixedCase()->numbers()
            : Password::min(8);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', $passwordRule],
            'role' => ['nullable', 'in:'.User::ROLE_ADMIN.','.User::ROLE_USER],
            'credits' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'] ?? User::ROLE_USER,
            'credits' => $validated['credits'] ?? $platformDefaults['default_credits'],
            'projects_count' => 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $this->transformUser($user),
        ], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'credits_delta' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (array_key_exists('credits_delta', $validated)) {
            $user->credits += $validated['credits_delta'];
        }

        if (array_key_exists('is_active', $validated)) {
            $user->is_active = $validated['is_active'];
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $this->transformUser($user->fresh()),
        ]);
    }

    private function transformUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'credits' => $user->credits,
            'stripe_customer_id' => $user->stripe_customer_id,
            'projects_count' => $user->projects_count,
            'registration_date' => optional($user->created_at)->toDateString(),
            'role' => $user->role,
            'is_active' => $user->is_active,
        ];
    }
}
