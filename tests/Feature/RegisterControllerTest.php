<?php

namespace Tests\Feature;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_access_the_registerPage():void
    {
        $response = $this->get("/cadastrar");

        $response->assertOk();
    }

    #[Test]
    public function user_can_register_with_valid_information():void
    {
        Event::fake();
        $response = $this->post("/cadastrar", [
            "first_name" => "Jenilson",
            "last_name" => "Lucas",
            "email" => "jenilsonllucas@gmail.com",
            "password" => "jenilson"
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas("users", [
            "email" => "jenilsonllucas@gmail.com"
        ]);
        Event::assertDispatched(Registered::class);
        $response->assertRedirectToRoute('verification.notice', ['email' => "jenilsonllucas@gmail.com"]);
    }

    #[Test]
    public function registration_required_all_fields():void
    {
        $response = $this->post("/cadastrar", [
            "first_name" => '',
            "last_name" => '',
            "email" => '',
            "password" => ''
        ]);

        $response->assertSessionHasErrors([
            "first_name", "last_name", "email", "password"
        ]);
    }

    #[Test]
    public function user_cannot_register_with_invalid_data():void
    {
        $response = $this->post("/cadastrar", [
            "first_name" => 11111111,
            "last_name" => 11111111,
            "email" => "jenilsonlucas",
            "password" => "1212"
        ]);

        $response->assertSessionHasErrors([
            "first_name", "last_name", "email", "password"
        ]);
    }
}
