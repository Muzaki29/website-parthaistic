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
        Schema::table('tugas', function (Blueprint $table) {
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('status_tugas');
            $table->dateTime('due_date')->nullable()->after('priority');
            $table->text('notes')->nullable()->after('deskripsi');
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
