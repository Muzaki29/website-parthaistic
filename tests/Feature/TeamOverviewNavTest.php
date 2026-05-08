<?php

namespace Tests\Feature;

use App\Livewire\TeamOverview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeamOverviewNavTest extends TestCase
{
    use RefreshDatabase;

    public function test_navigating_months_keeps_view_intact(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'status_akun' => 'active',
        ]);

        $this->actingAs($user);

        Livewire::test(TeamOverview::class)
            ->call('previousMonth')
            ->assertSee('Hall of Fame', false)
            ->assertSee('Daily Completed Tasks by Member', false)
            ->call('nextMonth')
            ->call('nextMonth')
            ->assertSee('Hall of Fame', false);
    }
}
