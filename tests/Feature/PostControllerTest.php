<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    
    #[Test]
    public function user_can_access_the_blogPage(): void
    {
        $response = $this->get("/blog");

        $response->assertOk();
    }

    #[Test] 
    public function blogPage_display_all_posts():void
    {
        Post::factory(10)->create();
        $response = $this->get("/blog");

        $response->assertViewHas("blog", function($blog){
            return $blog->count() == 6;
        });
    }

    #[Test]
    public function it_redirects_if_search_is_empty():void
    {
        $response = $this->post("/blog/buscar", ['s' => '']);    
        $response->assertRedirect("/blog");
    }

    #[Test]
    public function it_returned_matching_results_when_search_is_provided():void
    {
        Post::factory()->create([
            "title" => "Contas gerenciadas",
            "subtitle" => "Aqui podes gerenciar as suas contas avontade"
        ]);

        $response = $this->post('/blog/buscar', ['s' => 'contas']);

        $response->assertStatus(200);
        $response->assertViewIs('page.blog');
        $response->assertViewHas('blog');
        $response->assertViewHas('title', 'PESQUISA POR:');
        $response->assertViewHas('search', 'contas');

        $blog = $response->viewData('blog');
        $this->assertCount(1, $blog->items());
    }



}
