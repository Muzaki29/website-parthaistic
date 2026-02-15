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
        Schema::create('statistik', function (Blueprint $table) {
            $table->id();
            $table->integer('total_todo')->default(0);
            $table->integer('total_doing')->default(0);
            $table->integer('total_done')->default(0);
            $table->dateTime('diperbarui_pada');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistik');
    }
};
