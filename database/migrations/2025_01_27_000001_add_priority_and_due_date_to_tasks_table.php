<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('tugas')) {
            return;
        }

        Schema::table('tugas', function (Blueprint $table) {
            if (! Schema::hasColumn('tugas', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('status_tugas');
            }
            if (! Schema::hasColumn('tugas', 'due_date')) {
                $table->dateTime('due_date')->nullable()->after('priority');
            }
            if (! Schema::hasColumn('tugas', 'notes')) {
                $table->text('notes')->nullable()->after('deskripsi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn(['priority', 'due_date', 'notes']);
        });
    }
};
