<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name, 
            'email' => $this->faker->unique()->safeEmail, 
            'phone' => '+380' . $this->faker->unique()->randomNumber(9, true), 
            'position_id' => Position::inRandomOrder()->first()->id,
            'photo' => $this->faker->imageUrl(300, 300, 'people', true, 'User'),
        ];
    }
}