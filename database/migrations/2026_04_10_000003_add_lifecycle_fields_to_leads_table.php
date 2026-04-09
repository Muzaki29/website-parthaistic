<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('status', 32)->default('new')->after('user_agent');
            $table->text('notes')->nullable()->after('status');
            $table->foreignId('assigned_to')->nullable()->after('notes')->constrained('users')->nullOnDelete();
            $table->timestamp('contacted_at')->nullable()->after('assigned_to');
            $table->timestamp('last_activity_at')->nullable()->after('contacted_at');

            $table->index('status', 'leads_status_idx');
            $table->index('last_activity_at', 'leads_last_activity_idx');
        });

        if (Schema::hasTable('leads')) {
            DB::table('leads')->whereNull('last_activity_at')->update([
                'last_activity_at' => DB::raw('COALESCE(updated_at, created_at)'),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropIndex('leads_status_idx');
            $table->dropIndex('leads_last_activity_idx');
            $table->dropColumn([
                'status',
                'notes',
                'assigned_to',
                'contacted_at',
                'last_activity_at',
            ]);
        });
    }
};
