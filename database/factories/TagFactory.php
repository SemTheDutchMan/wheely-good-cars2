<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
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
            ['name' => '4x4', 'color' => '#0f172a'],
            ['name' => 'FWD', 'color' => '#0ea5e9'],
            ['name' => 'RWD', 'color' => '#1f2937'],
            ['name' => 'AWD', 'color' => '#111827'],
            ['name' => 'Zuinig', 'color' => '#84cc16'],
            ['name' => 'Gezinsauto', 'color' => '#22c55e'],
            ['name' => 'Compact', 'color' => '#38bdf8'],
            ['name' => 'Premium', 'color' => '#a855f7'],
            ['name' => 'Luxe', 'color' => '#facc15'],
            ['name' => 'Nieuwstaat', 'color' => '#10b981'],
            ['name' => 'Navigatie', 'color' => '#38bdf8'],
            ['name' => 'Cruise control', 'color' => '#0ea5e9'],
            ['name' => 'Parkeersensoren', 'color' => '#64748b'],
            ['name' => 'Parkeercamera', 'color' => '#0ea5e9'],
            ['name' => 'Apple CarPlay', 'color' => '#0ea5e9'],
            ['name' => 'Android Auto', 'color' => '#22c55e'],
            ['name' => 'Leder interieur', 'color' => '#a16207'],
            ['name' => 'speciaal', 'color' => '#f43f5e'],
            
        ];

        $tag = $this->faker->randomElement($tags);

        return [
            'name' => $tag['name'],
            'color' => $tag['color'],
        ];
    }
}
