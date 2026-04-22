<?php

namespace Database\Seeders;

use App\Models\Statistic;
use App\Models\Task;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $users = User::all();

        foreach ($users as $user) {
            // Generate 10-15 random tasks for each user
            $numberOfTasks = rand(10, 15);

            for ($i = 0; $i < $numberOfTasks; $i++) {
                Task::create([
                    'judul' => $faker->sentence(4),
                    'deskripsi' => $faker->paragraph(),
                    'status_tugas' => $faker->randomElement(Task::workflowStatuses()),
                    'assigned_to' => $user->id,
                    'dibuat' => now(),
                    'diperbarui' => now(),
                    'id_kartu_trello' => $faker->uuid,
                ]);
            }

            // Calculate statistics for the user
            $todoCount = Task::where('assigned_to', $user->id)
                ->whereIn('status_tugas', [
                    Task::STATUS_DROP_IDEA,
                    Task::STATUS_SCRIPT_IDEA,
                    Task::STATUS_SCRIPT_WRITTEN,
                    Task::STATUS_SCRIPT_PREVIEW,
                ])->count();
            $doingCount = Task::where('assigned_to', $user->id)
                ->whereIn('status_tugas', [
                    Task::STATUS_CREW_CALL_SHOOTING,
                    Task::STATUS_PRODUCTION,
                    Task::STATUS_POST_PRODUCTION,
                    Task::STATUS_PREVIEW,
                ])->count();
            $doneCount = Task::where('assigned_to', $user->id)->where('status_tugas', Task::STATUS_FINISHED)->count();

            // Create or update statistics
            Statistic::updateOrCreate(
                ['id_user' => $user->id],
                [
                    'total_todo' => $todoCount,
                    'total_doing' => $doingCount,
                    'total_done' => $doneCount,
                    'diperbarui_pada' => now(),
                ]
            );
        }
    }
}
