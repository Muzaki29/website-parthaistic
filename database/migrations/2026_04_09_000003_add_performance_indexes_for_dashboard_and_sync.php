<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->index(['status_tugas', 'diperbarui'], 'tugas_status_diperbarui_idx');
            $table->index(['assigned_to', 'status_tugas'], 'tugas_assigned_status_idx');
            $table->index(['priority', 'due_date'], 'tugas_priority_due_date_idx');
            $table->index('id_sinkron', 'tugas_id_sinkron_idx');
        });

        Schema::table('task_files', function (Blueprint $table) {
            $table->index(['task_id', 'uploaded_by'], 'task_files_task_uploaded_idx');
        });

        Schema::table('sinkronisasi_api', function (Blueprint $table) {
            $table->index(['id_user', 'waktu_sinkron'], 'sinkronisasi_user_waktu_idx');
            $table->index('status', 'sinkronisasi_status_idx');
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("
                DELETE s1 FROM statistik s1
                INNER JOIN statistik s2
                    ON s1.id_user = s2.id_user
                    AND s1.id < s2.id
            ");
        } elseif ($driver === 'sqlite') {
            DB::statement('
                DELETE FROM statistik
                WHERE id IN (
                    SELECT s.id FROM statistik s
                    WHERE EXISTS (
                        SELECT 1 FROM statistik s2
                        WHERE s2.id_user = s.id_user AND s2.id > s.id
                    )
                )
            ');
        }

        Schema::table('statistik', function (Blueprint $table) {
            $table->unique('id_user', 'statistik_id_user_unique');
            $table->index('diperbarui_pada', 'statistik_diperbarui_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statistik', function (Blueprint $table) {
            $table->dropUnique('statistik_id_user_unique');
            $table->dropIndex('statistik_diperbarui_idx');
        });

        Schema::table('sinkronisasi_api', function (Blueprint $table) {
            $table->dropIndex('sinkronisasi_user_waktu_idx');
            $table->dropIndex('sinkronisasi_status_idx');
        });

        Schema::table('task_files', function (Blueprint $table) {
            $table->dropIndex('task_files_task_uploaded_idx');
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->dropIndex('tugas_status_diperbarui_idx');
            $table->dropIndex('tugas_assigned_status_idx');
            $table->dropIndex('tugas_priority_due_date_idx');
            $table->dropIndex('tugas_id_sinkron_idx');
        });
    }
};

