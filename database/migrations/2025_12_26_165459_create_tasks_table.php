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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->string('id_kartu_trello')->unique();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status_tugas', ['To Do', 'Doing', 'Done'])->default('To Do');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->dateTime('due_date')->nullable();
            $table->dateTime('dibuat');
            $table->dateTime('diperbarui');
            $table->foreignId('id_sinkron')->nullable()->constrained('sinkronisasi_api')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
