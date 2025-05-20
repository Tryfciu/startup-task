<?php

namespace Database\Seeders;

use App\Models\Email;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function (User $user) {
            Email::factory(random_int(1, 2))->forUser($user)->create();
        });
    }
}
