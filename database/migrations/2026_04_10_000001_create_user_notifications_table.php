<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 64);
            $table->string('title');
            $table->text('message');
            $table->string('action_url', 2048)->nullable();
            $table->string('action_label', 120)->nullable();
            $table->json('data')->nullable();
            $table->string('source_key', 191)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at', 'dismissed_at'], 'user_notifications_user_read_dismissed_idx');
            $table->index(['user_id', 'created_at'], 'user_notifications_user_created_idx');
            $table->unique(['user_id', 'source_key'], 'user_notifications_user_source_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
