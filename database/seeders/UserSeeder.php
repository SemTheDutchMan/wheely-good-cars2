<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin gebruiker',
            'email' => 'admin@wheelygoodcars.test',
            'phone_number' => '0612345678',
            'is_admin' => true,
        ]);

        User::factory()->count(150)->create();
    }
}
