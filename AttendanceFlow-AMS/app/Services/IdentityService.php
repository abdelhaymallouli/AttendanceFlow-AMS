<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * IdentityService
 * 
 * Handles authentication, user management, and authorization.
 */
class IdentityService extends BaseService
{
    public function getServiceName(): string
    {
        return 'IdentityService';
    }

    /**
     * Authenticate a user with email and password.
     */
    public function authenticate(array $credentials, bool $remember = false): bool
    {
        $this->logInfo("Authentication attempt for email: " . ($credentials['email'] ?? 'unknown'));
        
        if (Auth::attempt($credentials, $remember)) {
            $this->logInfo("Authentication successful for email: " . $credentials['email']);
            return true;
        }

        $this->logInfo("Authentication failed for email: " . ($credentials['email'] ?? 'unknown'));
        return false;
    }

    /**
     * Register a new user and assign a role.
     */
    public function registerUser(array $data, string $roleName): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assuming Spatie Roles are seeded
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $user->assignRole($roleName);
        }

        $this->logInfo("User registered: {$user->email} with role: {$roleName}");

        return $user;
    }

    /**
     * Logout the current user.
     */
    public function logout(): void
    {
        if (Auth::check()) {
            $this->logInfo("User logged out: " . Auth::user()->email);
            Auth::logout();
        }
    }
}
