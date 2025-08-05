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
        if (Schema::hasTable('films'))
        {
            Schema::table('films', function (Blueprint $table) {
                $table->dropColumn('image_path', 'video_path');
                $table->string('directors')->after('length');
                $table->string('actors')->after('directors');
                $table->unsignedSmallInteger('year')->after('actors');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('films'))
        {
            Schema::table('films', function (Blueprint $table) {
                $table->dropColumn('directors', 'actors', 'year');
                $table->string('image_path');
                $table->string('video_path');
            });
        }
    }
};
