<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_welcome_page_shows_only_published_articles(): void
    {
        $author = User::factory()->create();

        Post::factory()->create([
            'user_id' => $author->id,
            'title' => 'A published article',
            'status' => 'published',
        ]);
        Post::factory()->create([
            'user_id' => $author->id,
            'title' => 'A draft article',
            'status' => 'draft',
        ]);
        Post::factory()->create([
            'user_id' => $author->id,
            'title' => 'A scheduled article',
            'status' => 'published',
            'published_at' => now()->addDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Article')
            ->assertSee('A published article')
            ->assertDontSee('A draft article')
            ->assertDontSee('A scheduled article');
    }
}
