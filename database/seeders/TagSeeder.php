<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Benzine', 'color' => '#0ea5e9'],
            ['name' => 'Diesel', 'color' => '#64748b'],
            ['name' => 'Elektrisch', 'color' => '#22c55e'],
            ['name' => 'Hybride', 'color' => '#16a34a'],
            ['name' => 'Automaat', 'color' => '#6366f1'],
            ['name' => 'Handgeschakeld', 'color' => '#8b5cf6'],
            ['name' => 'SUV', 'color' => '#f97316'],
            ['name' => 'Hatchback', 'color' => '#f59e0b'],
            ['name' => 'Station', 'color' => '#14b8a6'],
            ['name' => 'Sedan', 'color' => '#06b6d4'],
            ['name' => 'Cabrio', 'color' => '#ec4899'],
            ['name' => 'Coupe', 'color' => '#ef4444'],
            ['name' => 'MPV', 'color' => '#0ea5e9'],
            ['name' => '4x4', 'color' => '#5a6070'],
            ['name' => 'FWD', 'color' => '#0ea5e9'],
            ['name' => 'RWD', 'color' => '#6c6f72'],
            ['name' => 'AWD', 'color' => '#37486b'],
            ['name' => 'Zuinig', 'color' => '#84cc16'],
            ['name' => 'Gezinsauto', 'color' => '#22c55e'],
            ['name' => 'Compact', 'color' => '#38bdf8'],
        ];

        foreach ($tags as $tag) {
            Tag::query()->updateOrCreate(['name' => $tag['name']], $tag);
        }
    }
}
