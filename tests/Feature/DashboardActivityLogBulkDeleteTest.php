<?php

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardActivityLogBulkDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_bulk_delete_visible_activity_logs(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'active']);

        $a = ActivityLog::create([
            'user_id' => $admin->id,
            'event_type' => 'test_event_one',
            'subject_type' => null,
            'subject_id' => null,
            'meta' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test',
        ]);
        $b = ActivityLog::create([
            'user_id' => $admin->id,
            'event_type' => 'test_event_two',
            'subject_type' => null,
            'subject_id' => null,
            'meta' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test',
        ]);

        $this->actingAs($admin);

        Livewire::test(Dashboard::class)
            ->set('selectedActivityLogIds', [(string) $a->id, (string) $b->id])
            ->call('deleteSelectedActivityLogs')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('activity_logs', ['id' => $a->id]);
        $this->assertDatabaseMissing('activity_logs', ['id' => $b->id]);
    }

    public function test_employee_can_only_delete_own_activity_logs(): void
    {
        $employee = User::factory()->create(['role' => 'employee', 'status_akun' => 'active']);
        $other = User::factory()->create(['role' => 'admin', 'status_akun' => 'active']);

        $own = ActivityLog::create([
            'user_id' => $employee->id,
            'event_type' => 'own',
            'subject_type' => null,
            'subject_id' => null,
            'meta' => null,
            'ip_address' => null,
            'user_agent' => null,
        ]);
        $foreign = ActivityLog::create([
            'user_id' => $other->id,
            'event_type' => 'foreign',
            'subject_type' => null,
            'subject_id' => null,
            'meta' => null,
            'ip_address' => null,
            'user_agent' => null,
        ]);

        $this->actingAs($employee);

        Livewire::test(Dashboard::class)
            ->set('selectedActivityLogIds', [(string) $own->id, (string) $foreign->id])
            ->call('deleteSelectedActivityLogs')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('activity_logs', ['id' => $own->id]);
        $this->assertDatabaseHas('activity_logs', ['id' => $foreign->id]);
    }

    public function test_toggle_select_all_selects_and_clears_visible_ids(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'active']);
        $ids = [];
        foreach (['a', 'b', 'c'] as $evt) {
            $ids[] = ActivityLog::create([
                'user_id' => $admin->id,
                'event_type' => $evt,
                'subject_type' => null,
                'subject_id' => null,
                'meta' => null,
                'ip_address' => null,
                'user_agent' => null,
            ])->id;
        }

        $this->actingAs($admin);

        $c = Livewire::test(Dashboard::class);
        $c->call('toggleSelectAllActivity');
        $this->assertCount(3, $c->get('selectedActivityLogIds'));
        $this->assertEqualsCanonicalizing($ids, array_map('intval', $c->get('selectedActivityLogIds')));

        $c->call('toggleSelectAllActivity')
            ->assertSet('selectedActivityLogIds', []);
    }
}
