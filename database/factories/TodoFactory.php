<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(10, true);
        $title = substr($title, 0, 50);

        $description = $this->faker->paragraph(4, true);
        $description = substr($title, 0, 250);

        return [
            'title' => $title, // Generates a fake title
            'description' => $description, // Generates fake description
            'user_id' => \App\Models\User::factory(), // Creates a new User instance
            'due_date' => $this->faker->dateTimeBetween('now', '1 month'),
        ];
    }
}
