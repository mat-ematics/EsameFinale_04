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
        if (Schema::hasTable('tv_series'))
        {
            Schema::table('tv_series', function (Blueprint $table) {
                if (Schema::hasColumn('tv_series', 'image_path')) {   
                    $table->dropColumn('image_path');
                }
                if (!Schema::hasColumn('tv_series', 'directors')) {
                    $table->string('directors')->after('episode_count');
                    $table->string('actors')->after('directors');
                    $table->unsignedSmallInteger('start_year')->after('actors');
                    $table->unsignedSmallInteger('end_year')->after('start_year');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tv_series'))
        {
            Schema::table('tv_series', function (Blueprint $table) {
                $table->dropColumn('directors', 'actors', 'start_year', 'end_year');
                $table->string('image_path');
            });
        }
    }
};
