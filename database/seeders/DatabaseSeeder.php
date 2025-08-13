<?php

namespace Database\Seeders;

use App\Models\App_Wallet;
use App\Models\AppInvoice;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = User::factory()->create([
            "first_name" => "Test",
            "email" => "jenilsonllucas@gmail.com",
            "password" => "12345678"
        ]);
       
        Post::factory(20)->create();
       
        App_Wallet::factory(3)->create();
       
        AppInvoice::factory(12)
        ->state(new Sequence(
            ["type" => "income"],
            ["type" => "expense"],
            ["status" => "paid"],
            ["status" => "unpaid"] 
        ))
        ->create([
            "user_id" => $user
        ]);
    }
}
