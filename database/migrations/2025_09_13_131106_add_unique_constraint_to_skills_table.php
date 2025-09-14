<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, remove duplicate skills (keep the first occurrence of each unique name+category)
        $duplicates = \DB::table('skills')
            ->select('name', 'category', \DB::raw('MIN(skill_id) as min_id'))
            ->groupBy('name', 'category')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            \DB::table('skills')
                ->where('name', $duplicate->name)
                ->where('category', $duplicate->category)
                ->where('skill_id', '!=', $duplicate->min_id)
                ->delete();
        }

        // Add unique constraint
        Schema::table('skills', function (Blueprint $table) {
            $table->unique(['name', 'category'], 'skills_name_category_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropUnique('skills_name_category_unique');
        });
    }
};
