<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
class PageControllerTest extends TestCase
{   
    use RefreshDatabase;

    #[Test]
    public function user_can_access_the_homePage():void
    {
        $response = $this->get("/");
        $response->assertOk();
    }

    #[Test]
    public function homepage_display_lastest_blog():void
    {
        Post::factory(10)->create();

        $response = $this->get("/");
        $response->assertViewHas('blog', function($blog) {
            return $blog->count() == 6;
        });
    }

    #[Test]
    public function user_can_access_the_aboutPage():void
    {
        $response = $this->get("/sobre");
        $response->assertOk();
    }
}
