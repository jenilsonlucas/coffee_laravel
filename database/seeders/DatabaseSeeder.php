<?php

namespace Database\Seeders;

use App\Models\App_Category;
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

        // $user = User::factory()->create([
        //     "first_name" => "Test",
        //     "email" => "test@gmail.com",
        //     "password" => "12345678"
        // ]);
       
        // Post::factory(20)->create();
        
        App_Category::factory()->create([
            "name" => "Sálario",
            "type" => "income",
            "order_by" => 0
        ]);

        App_Category::factory()->create([
            "name" => "Investimento",
            "type" => "income",
            "order_by" => 1
        ]);

        App_Category::factory()->create([
            "name" => "Empréstimo",
            "type" => "income",
            "order_by" => 1
        ]);


        App_Category::factory()->create([
            "name" => "Outras Receitas",
            "type" => "income",
            "order_by" => 2
        ]);

        App_Category::factory()->create([
            "name" => "Alimentação",
            "type" => "Expense",
            "order_by" => 0
        ]);

        App_Category::factory()->create([
            "name" => "Aluguel",
            "type" => "expense",
            "order_by" => 0
        ]);

        App_Category::factory()->create([
            "name" => "Compras",
            "type" => "expense",
            "order_by" => 0
        ]);

        App_Category::factory()->create([
            "name" => "Educação",
            "type" => "expense",
            "order_by" => 0
        ]);


        App_Category::factory()->create([
            "name" => "Entretenimento",
            "type" => "expense",
            "order_by" => 0
        ]);


        App_Category::factory()->create([
            "name" => "Impostos e taxas",
            "type" => "expense",
            "order_by" => 0
        ]);


        App_Category::factory()->create([
            "name" => "Sáúde",
            "type" => "expense",
            "order_by" => 0
        ]);

        App_Category::factory()->create([
            "name" => "Serviços",
            "type" => "expense",
            "order_by" => 0
        ]);


        App_Category::factory()->create([
            "name" => "Viagem",
            "type" => "expense",
            "order_by" => 0
        ]);

        App_Category::factory()->create([
            "name" => "Outras dispesas",
            "type" => "expense",
            "order_by" => 2
        ]);

        // App_Wallet::factory(3)->create();
       
        // AppInvoice::factory(12)
        // ->state(new Sequence(
        //     ["type" => "income"],
        //     ["type" => "expense"],
        //     ["status" => "paid"],
        //     ["status" => "unpaid"] 
        // ))
        // ->create([
        //     "user_id" => $user
        // ]);
    }
}
