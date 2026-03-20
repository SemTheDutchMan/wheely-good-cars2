<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();
        $tags = Tag::all();

        Car::factory()
            ->count(250)
            ->make()
            ->each(function (Car $car) use ($users, $tags) {
                $car->user_id = $users->random()->id;
                $car->save();

                $pickedTags = $tags->shuffle()->take(rand(0, 3))->pluck('id')->all();
                $car->tags()->sync($pickedTags);
            });
    }
}
