<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRedirectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_redirected_to_admin_dashboard()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_support_is_redirected_to_support_dashboard()
    {
        $support = User::factory()->create([
            'role' => 'support',
        ]);

        $response = $this->post('/login', [
            'email' => $support->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('support.dashboard', absolute: false));
    }

    public function test_user_is_redirected_to_user_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
