<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_post_with_category_and_tags()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech']);
        $tag1 = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $tag2 = Tag::create(['name' => 'PHP', 'slug' => 'php']);

        $response = $this->actingAs($admin)->post(route('admin.posts.store'), [
            'title' => 'New Post',
            'content' => 'Content here',
            'category_id' => $category->id,
            'tags' => [$tag1->id, $tag2->id],
            'is_published' => '1',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        
        $this->assertDatabaseHas('posts', [
            'title' => 'New Post',
            'category_id' => $category->id,
        ]);

        $post = Post::where('title', 'New Post')->first();
        $this->assertCount(2, $post->tags);
        $this->assertTrue($post->tags->contains($tag1));
        $this->assertTrue($post->tags->contains($tag2));
    }

    public function test_admin_can_update_post_with_category_and_tags()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Tech', 'slug' => 'tech']);
        $newCategory = Category::create(['name' => 'Life', 'slug' => 'life']);
        $tag1 = Tag::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $tag2 = Tag::create(['name' => 'PHP', 'slug' => 'php']);
        
        $post = Post::create([
            'title' => 'Old Post',
            'slug' => 'old-post',
            'content' => 'Old Content',
            'user_id' => $admin->id,
            'category_id' => $category->id,
        ]);
        $post->tags()->attach($tag1);

        $response = $this->actingAs($admin)->put(route('admin.posts.update', $post), [
            'title' => 'Updated Post',
            'content' => 'Updated Content',
            'category_id' => $newCategory->id,
            'tags' => [$tag2->id],
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post',
            'category_id' => $newCategory->id,
        ]);

        $post->refresh();
        $this->assertCount(1, $post->tags);
        $this->assertTrue($post->tags->contains($tag2));
        $this->assertFalse($post->tags->contains($tag1));
    }
}
