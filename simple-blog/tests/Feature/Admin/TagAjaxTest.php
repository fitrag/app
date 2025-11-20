<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagAjaxTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_tag_via_ajax()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->postJson(route('admin.tags.store'), [
            'name' => 'Ajax Tag',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Tag created successfully.',
                     'tag' => [
                         'name' => 'Ajax Tag',
                         'slug' => 'ajax-tag',
                     ],
                 ]);

        $this->assertDatabaseHas('tags', [
            'name' => 'Ajax Tag',
        ]);
    }
}
