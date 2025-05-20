<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EmailFactory extends Factory
{
    public function forUser(User $user): self
    {
        return $this->state(['user_id' => $user->id]);
    }

    public function definition(): array
    {
        return [
            'email' => fake()->name(),
        ];
    }
}
