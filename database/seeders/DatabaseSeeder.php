<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make("password");

        \App\Models\User::factory()->create([
            "email" => "pedro@email.com",
            "password" => $password
        ]);
    }
}
