<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // SQLite stores enum as text-like type, no change needed.
            return;
        }

        DB::statement("
            ALTER TABLE tugas
            MODIFY status_tugas VARCHAR(64) NOT NULL DEFAULT 'Drop idea'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

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
};

