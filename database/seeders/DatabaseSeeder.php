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
        $password = Hash::make('cF1Up43$Dm@3Vubd');

        \App\Models\User::factory()->create([
            "email" => "pedrommartin97@gmail.com",
            "password" => $password
        ]);
    }
}
