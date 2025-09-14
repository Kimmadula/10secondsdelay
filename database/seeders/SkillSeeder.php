<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            ['name' => 'Web Development', 'category' => 'IT'],
            ['name' => 'Graphic Design', 'category' => 'Design'],
            ['name' => 'Cooking', 'category' => 'Culinary'],
        ];

        foreach ($skills as $skillData) {
            Skill::firstOrCreate(
                ['name' => $skillData['name'], 'category' => $skillData['category']],
                $skillData
            );
        }
    }
}
