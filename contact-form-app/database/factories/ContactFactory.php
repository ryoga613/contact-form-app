<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $faker = \Faker\Factory::create('ja_JP');
        return [
            'first_name' => $faker->lastName(),
            'last_name' => $faker->firstName(),
            'gender' => $faker->numberBetween(1, 3),
            'email' => $faker->unique()->safeEmail(),
            'tel' => preg_replace('/[^0-9]/', '', $faker->phoneNumber()),
            'address' => $faker->address(),
            'building' => $faker->secondaryAddress(),
            'detail' => $faker->realText(100),
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
