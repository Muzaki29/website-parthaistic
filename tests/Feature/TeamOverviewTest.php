<?php

namespace Tests\Feature;

use App\Livewire\TeamOverview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeamOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_team_overview(): void
    {
        $this->get(route('team.overview'))->assertRedirect(route('login'));
    }

    public function test_authenticated_employee_can_view_team_overview(): void
    {
        $user = User::factory()->create([
            'role' => 'employee',
            'status_akun' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('team.overview'))
            ->assertOk()
            ->assertSee('Team Overview', false)
            ->assertSee('Daily Completed Tasks by Member', false)
            ->assertSee('Hall of Fame', false);
    }

    public function test_open_and_close_member_modal_updates_state(): void
    {
        $viewer = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'active',
        ]);

        $member = User::factory()->create([
            'role' => 'employee',
            'status_akun' => 'active',
            'jabatan' => 'Video Editor',
        ]);

        $this->actingAs($viewer);

        Livewire::test(TeamOverview::class)
            ->assertSet('showMemberModal', false)
            ->call('openMember', $member->id)
            ->assertSet('showMemberModal', true)
            ->assertSet('selectedMemberId', $member->id)
            ->assertSee($member->name)
            ->assertSee('Pencapaian Best Employee', false)
            ->call('closeMember')
            ->assertSet('showMemberModal', false)
            ->assertSet('selectedMemberId', null);
    }

    public function test_month_navigation_updates_period(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'active',
        ]);

        $this->actingAs($user);

        $current = now();

        Livewire::test(TeamOverview::class)
            ->assertSet('overviewMonth', (int) $current->month)
            ->assertSet('overviewYear', (int) $current->year)
            ->call('previousMonth')
            ->assertSet('overviewMonth', (int) $current->copy()->subMonth()->month)
            ->call('nextMonth')
            ->call('nextMonth')
            ->assertSet('overviewMonth', (int) $current->copy()->addMonth()->month);
    }
}
