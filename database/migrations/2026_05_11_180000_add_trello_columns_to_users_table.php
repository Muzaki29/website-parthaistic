<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'trello_member_id')) {
                $table->string('trello_member_id', 64)->nullable()->after('jabatan');
                $table->unique('trello_member_id', 'users_trello_member_id_unique');
            }
            if (! Schema::hasColumn('users', 'trello_username')) {
                $table->string('trello_username', 64)->nullable()->after('trello_member_id');
                $table->index('trello_username', 'users_trello_username_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'trello_username')) {
                $table->dropIndex('users_trello_username_index');
                $table->dropColumn('trello_username');
            }
            if (Schema::hasColumn('users', 'trello_member_id')) {
                $table->dropUnique('users_trello_member_id_unique');
                $table->dropColumn('trello_member_id');
            }
        });
    }
};
