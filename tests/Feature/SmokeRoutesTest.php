<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SmokeRoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Hit all main GET pages with an authenticated admin and assert no 500.
     *
     * @return array<string, array{0:string,1:int}>
     */
    public static function adminRoutes(): array
    {
        return [
            'landing' => ['/', 200],
            'dashboard' => ['/dashboard', 200],
            'team-overview' => ['/team-overview', 200],
            'workflow-board' => ['/workflow-board', 200],
            'reports' => ['/reports', 200],
            'profile' => ['/profile', 200],
            'notifications' => ['/notifications', 200],
            'employees' => ['/employees', 200],
            'admin-leads' => ['/admin/leads', 200],
            'trello-mapping' => ['/admin/trello-mapping', 200],
        ];
    }

    #[DataProvider('adminRoutes')]
    public function test_admin_can_access_route(string $url, int $expected): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'active',
        ]);

        $response = $this->actingAs($admin)->get($url);
        $this->assertSame(
            $expected,
            $response->getStatusCode(),
            "GET {$url} returned {$response->getStatusCode()} instead of {$expected}"
        );
    }

    public function test_employee_cannot_access_admin_only_routes(): void
    {
        $employee = User::factory()->create([
            'role' => 'employee',
            'status_akun' => 'active',
        ]);

        $this->actingAs($employee)->get('/employees')->assertForbidden();
        $this->actingAs($employee)->get('/admin/leads')->assertForbidden();
        $this->actingAs($employee)->get('/admin/trello-mapping')->assertForbidden();
    }

    public function test_guest_redirects_to_login_for_protected_routes(): void
    {
        foreach (['/dashboard', '/team-overview', '/workflow-board', '/reports', '/profile', '/notifications'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_authenticated_user_redirected_away_from_auth_pages(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'active',
        ]);

        $this->actingAs($admin)->get('/login')->assertRedirect('/dashboard');
        $this->actingAs($admin)->get('/register')->assertRedirect('/dashboard');
    }

    public function test_guest_can_view_auth_pages(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_inactive_user_is_logged_out_when_hitting_protected_route(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'suspended',
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect('/login');

        $this->assertGuest();
    }
}
