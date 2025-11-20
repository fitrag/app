<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_posts_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.posts.index'));

        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_posts_index()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.posts.index'));

        $response->assertRedirect('/');
    }

    public function test_guest_cannot_access_admin_posts_index()
    {
        $response = $this->get(route('admin.posts.index'));

        $response->assertRedirect('/login');
    }
}
