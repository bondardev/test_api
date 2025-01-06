<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name, // Имя пользователя
            'email' => $this->faker->unique()->safeEmail, // Email
            'phone' => '+380' . $this->faker->unique()->randomNumber(9, true), // Телефон
            'position_id' => Position::inRandomOrder()->first()->id, // Случайная связанная позиция
            'photo' => $this->faker->imageUrl(300, 300, 'people', true, 'User'), // Ссылка на внешнее изображение
        ];
    }
}