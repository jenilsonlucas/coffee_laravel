<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_access_loginPage():void
    {
        $response = $this->get("/entrar");

        $response->assertOk();
    }

    #[Test]
    public function user_can_login_with_valid_credentials():void
    {
        $user = User::factory()->create([
            "email" => "jenilsonllucas@gmail.com",
            "password" => bcrypt("12345678")
        ]);

        $response = $this->post("/entrar", [
            "email" => $user->email,
            "password" => "12345678"
        ]);

        $response->assertRedirect("/app");
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function user_can_login_with_remember():void
    {
        $user = User::factory()->create([
            "email" => "jenilsonllucas@gmail.com",
            "password" => bcrypt("12345678")
        ]);

        $response = $this->post("/entrar",[
            "email" => $user->email,
            "password" => "12345678",
            "remember" => 1
        ]);

        $response->assertRedirect("/app");
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function session_is_regenerate_after_login():void
    {
        $user = User::factory()->create([
            "password" => bcrypt("12345678")
        ]);

        $this->startSession();

        $oldSession = session()->getId();

        $this->post("/entrar", [
            "email" => $user->email,
            "password" => "12345678"
        ]);

        $newSession = session()->getId();

        $this->assertNotEquals($oldSession, $newSession);

    }

    #[Test]
    public function user_cannot_login_with_invalid_credentials():void
    {
        $user = User::factory()->create();
        $response = $this->post("/entrar", [
            "email" => $user->email,
            "password" => 'passworderrada'
        ]);

        $this->assertGuest();
        $response->assertSessionHas("credentials");
        $response->assertRedirectBack();
    }

    #[Test]
    public function login_required_all_filds_less_remember():void
    {
        $response = $this->post("/entrar", [
            "email" => "",
            "password" => ""
        ]);

        $response->assertSessionHasErrors(["email", "password"]);
    }
}
