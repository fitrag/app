<?php

namespace Tests\Feature\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_tags_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.tags.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.tags.index');
    }

    public function test_admin_can_create_tag()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.tags.store'), [
            'name' => 'Test Tag',
        ]);

        $response->assertRedirect(route('admin.tags.index'));
        $this->assertDatabaseHas('tags', [
            'name' => 'Test Tag',
            'slug' => 'test-tag',
        ]);
    }

    public function test_admin_can_update_tag()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tag = Tag::create(['name' => 'Old Tag', 'slug' => 'old-tag']);

        $response = $this->actingAs($admin)->put(route('admin.tags.update', $tag), [
            'name' => 'New Tag',
        ]);

        $response->assertRedirect(route('admin.tags.index'));
        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'New Tag',
            'slug' => 'new-tag',
        ]);
    }

    public function test_admin_can_delete_tag()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tag = Tag::create(['name' => 'To Delete', 'slug' => 'to-delete']);

        $response = $this->actingAs($admin)->delete(route('admin.tags.destroy', $tag));

        $response->assertRedirect(route('admin.tags.index'));
        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }
}
