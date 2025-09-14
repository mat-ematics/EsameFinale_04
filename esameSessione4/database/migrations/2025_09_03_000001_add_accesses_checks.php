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
        if (Schema::hasTable('accesses')) {
            Schema::table('accesses', function (Blueprint $table) {
                $table->string('user_hash')->after('ip');
                $table->timestamp('attempt_start_at')->after('attempts');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('accesses') && Schema::hasColumn('accesses', 'email')) {
            Schema::table('accesses', function (Blueprint $table) {
                $table->dropColumn('user_hash', 'attempt_start_at');
            });
        }
    }
};
