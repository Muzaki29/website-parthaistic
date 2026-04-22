<?php

use App\Models\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite test environment: skip enum alteration to avoid syntax incompatibility.
            return;
        }

        // 1) Expand enum first so old and new values are both valid.
        DB::statement("
            ALTER TABLE tugas
            MODIFY status_tugas ENUM(
                'To Do',
                'Doing',
                'Done',
                'Drop idea',
                'Script idea',
                'Script written',
                'Script preview',
                'Crew call shooting',
                'Production',
                'Post - Production',
                'Preview',
                'Finished'
            ) NOT NULL DEFAULT 'To Do'
        ");

        // 2) Map old values to the new workflow.
        DB::table('tugas')
            ->where('status_tugas', 'To Do')
            ->update(['status_tugas' => Task::STATUS_DROP_IDEA]);

        DB::table('tugas')
            ->where('status_tugas', 'Doing')
            ->update(['status_tugas' => Task::STATUS_PRODUCTION]);

        DB::table('tugas')
            ->where('status_tugas', 'Done')
            ->update(['status_tugas' => Task::STATUS_FINISHED]);

        // 3) Narrow enum to only new workflow values.
        DB::statement("
            ALTER TABLE tugas
            MODIFY status_tugas ENUM(
                'Drop idea',
                'Script idea',
                'Script written',
                'Script preview',
                'Crew call shooting',
                'Production',
                'Post - Production',
                'Preview',
                'Finished'
            ) NOT NULL DEFAULT 'Drop idea'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // 1) Expand enum so rollback remap values are accepted.
        DB::statement("
            ALTER TABLE tugas
            MODIFY status_tugas ENUM(
                'To Do',
                'Doing',
                'Done',
                'Drop idea',
                'Script idea',
                'Script written',
                'Script preview',
                'Crew call shooting',
                'Production',
                'Post - Production',
                'Preview',
                'Finished'
            ) NOT NULL DEFAULT 'Drop idea'
        ");

        // 2) Map new values back to legacy statuses.
        DB::table('tugas')
            ->where('status_tugas', Task::STATUS_DROP_IDEA)
            ->update(['status_tugas' => 'To Do']);

        DB::table('tugas')
            ->whereIn('status_tugas', [
                Task::STATUS_SCRIPT_IDEA,
                Task::STATUS_SCRIPT_WRITTEN,
                Task::STATUS_SCRIPT_PREVIEW,
                Task::STATUS_CREW_CALL_SHOOTING,
                Task::STATUS_PRODUCTION,
                Task::STATUS_POST_PRODUCTION,
                Task::STATUS_PREVIEW,
            ])
            ->update(['status_tugas' => 'Doing']);

        DB::table('tugas')
            ->where('status_tugas', Task::STATUS_FINISHED)
            ->update(['status_tugas' => 'Done']);

        // 3) Narrow enum back to old values.
        DB::statement("
            ALTER TABLE tugas
            MODIFY status_tugas ENUM('To Do', 'Doing', 'Done') NOT NULL DEFAULT 'To Do'
        ");
    }
};

