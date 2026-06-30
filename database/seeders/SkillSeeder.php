<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            ['name' => 'Electrician', 'icon' => 'bolt', 'description' => 'Electrical wiring, repairs, and installations'],
            ['name' => 'Plumber', 'icon' => 'wrench', 'description' => 'Pipe fitting, leak repairs, and bathroom installations'],
            ['name' => 'AC Technician', 'icon' => 'snowflake', 'description' => 'AC installation, servicing, and repair'],
            ['name' => 'Carpenter', 'icon' => 'hammer', 'description' => 'Furniture making, wood repairs, and installations'],
            ['name' => 'Painter', 'icon' => 'paint-brush', 'description' => 'Interior and exterior painting services'],
            ['name' => 'Tutor', 'icon' => 'book', 'description' => 'Academic tutoring and coaching'],
            ['name' => 'Cleaner', 'icon' => 'sparkles', 'description' => 'House cleaning and deep cleaning services'],
            ['name' => 'Appliance Repair', 'icon' => 'tv', 'description' => 'Repair of home appliances like fridge, washing machine, etc.'],
            ['name' => 'Gardener', 'icon' => 'leaf', 'description' => 'Garden maintenance, landscaping, and plant care'],
            ['name' => 'Mason', 'icon' => 'building', 'description' => 'Brickwork, tiling, and construction repairs'],
            ['name' => 'Welder', 'icon' => 'fire', 'description' => 'Metal welding, gate making, and grill work'],
            ['name' => 'IT Support', 'icon' => 'computer', 'description' => 'Computer repair, networking, and tech support'],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(
                ['name' => $skill['name']],
                $skill
            );
        }
    }
}
