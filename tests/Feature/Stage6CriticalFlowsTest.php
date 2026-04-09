<?php

namespace Tests\Feature;

use App\Livewire\Employees;
use App\Livewire\Notifications;
use App\Livewire\Register;
use App\Models\Lead;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class Stage6CriticalFlowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    protected function makeUser(string $role = 'employee'): User
    {
        return User::factory()->create([
            'role' => $role,
            'status_akun' => 'active',
        ]);
    }

    public function test_lead_submission_success_creates_lead_and_logs_activity(): void
    {
        $this->makeUser('admin');

        $response = $this->post(route('leads.store'), [
            'name' => 'Jane Client',
            'email' => 'jane@gmail.com',
            'company' => 'ACME',
            'project_brief' => 'Butuh landing page untuk produk digital.',
            'form_rendered_at' => now()->timestamp - 120,
            'website' => '',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leads', [
            'email' => 'jane@gmail.com',
            'status' => Lead::STATUS_NEW,
        ]);
        $this->assertDatabaseHas('activity_logs', [
            'event_type' => 'lead_submitted',
        ]);
    }

    public function test_lead_submission_validation_failure(): void
    {
        $response = $this->post(route('leads.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'project_brief' => 'x',
            'form_rendered_at' => now()->timestamp - 120,
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_lead_honeypot_is_silent_success_without_persisting(): void
    {
        $this->post(route('leads.store'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'project_brief' => 'Spam content here.',
            'form_rendered_at' => now()->timestamp - 120,
            'website' => 'http://spam.example',
        ]);

        $this->assertDatabaseCount('leads', 0);
    }

    public function test_register_auto_login_redirects_to_dashboard(): void
    {
        Livewire::test(Register::class)
            ->set('name', 'New User')
            ->set('email', 'newuser@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_admin_only_leads_index(): void
    {
        $admin = $this->makeUser('admin');
        $employee = $this->makeUser('employee');

        $this->get(route('admin.leads.index'))->assertRedirect(route('login'));
        $this->actingAs($employee)->get(route('admin.leads.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.leads.index'))->assertOk();
    }

    public function test_admin_only_employees_mutation(): void
    {
        $employee = $this->makeUser('employee');

        $this->actingAs($employee);

        Livewire::test(Employees::class)
            ->set('newName', 'X')
            ->set('newEmail', 'x@example.com')
            ->set('newPassword', 'passwordxx')
            ->set('newRole', 'manager')
            ->call('createUser')
            ->assertForbidden();
    }

    public function test_notifications_mark_read_and_mark_all_read(): void
    {
        $user = $this->makeUser('manager');

        $n = UserNotification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => 'Test',
            'message' => 'Hello',
            'action_url' => '/dashboard',
            'read_at' => null,
            'dismissed_at' => null,
        ]);

        $this->actingAs($user)->get(route('notifications.open', $n));

        $n->refresh();
        $this->assertNotNull($n->read_at);
        $this->assertDatabaseHas('activity_logs', [
            'event_type' => 'notification_read',
            'user_id' => $user->id,
        ]);

        $n2 = UserNotification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => 'Test 2',
            'message' => 'Hello 2',
            'action_url' => '/reports',
            'read_at' => null,
            'dismissed_at' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(Notifications::class)
            ->call('markAllAsRead');

        $n2->refresh();
        $this->assertNotNull($n2->read_at);
    }

    public function test_lead_status_transition_logs_activity(): void
    {
        $admin = $this->makeUser('admin');
        $lead = Lead::create([
            'name' => 'A',
            'email' => 'a@example.com',
            'company' => null,
            'project_brief' => 'Brief',
            'source' => 'test',
            'status' => Lead::STATUS_NEW,
            'last_activity_at' => now(),
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.leads.update', $lead), [
                'status' => Lead::STATUS_CONTACTED,
            ])
            ->assertRedirect();

        $lead->refresh();
        $this->assertSame(Lead::STATUS_CONTACTED, $lead->status);
        $this->assertDatabaseHas('activity_logs', [
            'event_type' => 'lead_status_changed',
            'user_id' => $admin->id,
        ]);
    }
}
