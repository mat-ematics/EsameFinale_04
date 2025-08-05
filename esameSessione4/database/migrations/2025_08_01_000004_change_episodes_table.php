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
        if (Schema::hasTable('episodes') && false)
        {
            Schema::table('episodes', function (Blueprint $table) {
                $table->dropColumn(['image_path', 'video_path']);
                $table->time('length')->after('episode_number');
                $table->unsignedSmallInteger('year')->after('length');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('episodes'))
        {
            Schema::table('episodes', function (Blueprint $table) {
                $table->dropColumn('length', 'year');
                $table->string('image_path');
                $table->string('video_path');
            });
        }
    }
};
