<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Services\IdentityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class IdentityServiceTest extends TestCase
{
    use RefreshDatabase;

    protected IdentityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new IdentityService();
        
        // Ensure roles exist for testing
        \Spatie\Permission\Models\Role::create(['name' => 'student']);
        \Spatie\Permission\Models\Role::create(['name' => 'teacher']);
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    }

    public function test_it_can_authenticate_a_valid_user(): void
    {
        $password = 'secret123';
        User::factory()->create([
            'email'    => 'admin@example.com',
            'password' => Hash::make($password),
        ]);

        $result = $this->service->authenticate([
            'email'    => 'admin@example.com',
            'password' => $password,
        ]);

        $this->assertTrue($result);
        $this->assertTrue(Auth::check());
    }

    public function test_it_fails_authentication_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'admin@example.com',
            'password' => Hash::make('correctpassword'),
        ]);

        $result = $this->service->authenticate([
            'email'    => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertFalse($result);
        $this->assertFalse(Auth::check());
    }

    public function test_it_fails_authentication_for_nonexistent_user(): void
    {
        $result = $this->service->authenticate([
            'email'    => 'ghost@example.com',
            'password' => 'anypassword',
        ]);

        $this->assertFalse($result);
    }

    public function test_it_can_register_a_new_user(): void
    {
        $data = [
            'name'     => 'John Doe',
            'email'    => 'john@example.com',
            'password' => 'password123',
        ];

        $user = $this->service->registerUser($data, 'student');

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_it_can_logout_an_authenticated_user(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $this->assertTrue(Auth::check());

        $this->service->logout();

        $this->assertFalse(Auth::check());
    }

    public function test_logout_does_nothing_when_not_authenticated(): void
    {
        // Should not throw any exception
        $this->service->logout();

        $this->assertFalse(Auth::check());
    }
}
