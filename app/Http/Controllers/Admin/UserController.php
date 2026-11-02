<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                abort_unless(Auth::check() && Auth::user()->isAdmin(), 403, 'Access denied. Administrator privileges required.');

                return $next($request);
            },
        ];
    }

    /**
     * Display a listing of all users with search and filters.
     */
    public function index(Request $request): View
    {
        $query = User::withCount(['services', 'domains', 'invoices'])->latest();

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role') && in_array($request->input('role'), ['admin', 'customer'], true)) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('status') && in_array($request->input('status'), ['active', 'suspended'], true)) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->paginate(15)->withQueryString();

        $counts = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'customers' => User::where('role', 'customer')->count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'total_balance' => (float) User::sum('balance'),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'country' => ['nullable', 'string', 'max:100'],
            'role' => ['required', Rule::in(['admin', 'customer'])],
            'status' => ['required', Rule::in(['active', 'suspended'])],
            'balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
            'address' => $validated['address'] ?? null,
            'country' => $validated['country'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'balance' => (float) ($validated['balance'] ?? 0.00),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} ({$user->email}) created successfully.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $user->loadCount(['services', 'domains', 'invoices']);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'country' => ['nullable', 'string', 'max:100'],
            'role' => ['required', Rule::in(['admin', 'customer'])],
            'status' => ['required', Rule::in(['active', 'suspended'])],
            'balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Prevent superadmin from accidentally demoting or suspending themselves
        if ($user->id === Auth::id()) {
            if ($validated['role'] !== 'admin') {
                return back()->with('error', 'You cannot remove your own Super Admin role.');
            }
            if ($validated['status'] !== 'active') {
                return back()->with('error', 'You cannot suspend your own account.');
            }
        }

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company' => $validated['company'] ?? null,
            'address' => $validated['address'] ?? null,
            'country' => $validated['country'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'balance' => (float) ($validated['balance'] ?? 0.00),
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} updated successfully.");
    }

    /**
     * Toggle user active/suspended status.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        return back()->with('success', "User {$user->name} status changed to " . strtoupper($newStatus) . '.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own Super Admin account.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last remaining Super Admin account.');
        }

        $name = $user->name;
        $email = $user->email;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} ({$email}) has been deleted.");
    }
}
