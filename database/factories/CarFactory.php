<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    public function definition(): array
    {
        $makesAndModels = [
            'Ferrari' => ['488 GTB', 'F8 Tributo', 'Roma', 'SF90 Stradale'],
            'Lamborghini' => ['Huracan', 'Aventador', 'Urus'],
            'McLaren' => ['570S', '720S', 'Artura'],
            'Porsche' => ['911', 'Panamera', 'Cayenne', 'Taycan'],
            'BMW' => ['3 Series', '5 Series', 'X5', 'M3', 'M5'],
            'Audi' => ['A4', 'A6', 'Q7', 'RS6', 'e-tron GT'],
            'Mercedes' => ['C-Class', 'E-Class', 'GLE', 'AMG GT', 'A45 AMG'],
            'Tesla' => ['Model S', 'Model 3', 'Model X', 'Model Y'],
            'Volvo' => ['XC40', 'XC60', 'XC90', 'V60'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Polo'],
            'Toyota' => ['Corolla', 'RAV4', 'Prius', 'Yaris'],
            'Ford' => ['Focus', 'Fiesta', 'Mustang', 'Explorer'],
            'Peugeot' => ['208', '308', '3008'],
            'Renault' => ['Clio', 'Megane', 'Captur'],
            'Skoda' => ['Octavia', 'Superb', 'Kodiaq'],
            'Hyundai' => ['i30', 'Tucson', 'Kona'],
            'Kia' => ['Ceed', 'Sportage', 'EV6'],
        ];

        $make = $this->faker->randomElement(array_keys($makesAndModels));
        $model = $this->faker->randomElement($makesAndModels[$make]);
        $productionYear = $this->faker->numberBetween(2000, 2024);

        $price = match ($make) {
            'Ferrari', 'Lamborghini', 'McLaren' => $this->faker->numberBetween(180000, 450000),
            'Porsche', 'BMW', 'Audi', 'Mercedes', 'Tesla', 'Volvo' => $this->faker->numberBetween(35000, 150000),
            default => $this->faker->numberBetween(5000, 45000),
        };

        return [
            'license_plate' => strtoupper($this->faker->unique()->bothify('##??##')),
            'make' => $make,
            'model' => $model,
            'price' => $price,
            'mileage' => $this->faker->numberBetween(0, 220000),
            'seats' => $this->faker->randomElement([2, 4, 5, 7]),
            'doors' => $this->faker->randomElement([2, 3, 4, 5]),
            'production_year' => $productionYear,
            'weight' => $this->faker->numberBetween(900, 2500),
            'color' => $this->faker->safeColorName(),
            'views' => $this->faker->numberBetween(0, 5000),
            'sold_at' => $this->faker->optional(0.15)->dateTimeThisYear(),
            'image' => null,
        ];
    }
}
