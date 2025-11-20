<?php

namespace Tests\Feature\Admin;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_menu_seeder_seeds_users_menu()
    {
        $this->seed(\Database\Seeders\AdminMenuSeeder::class);

        $this->assertDatabaseHas('menus', [
            'label' => 'Users',
            'url' => 'admin.users.index',
            'location' => 'admin',
        ]);
    }

    public function test_user_controller_fetches_users_menu()
    {
        $this->seed(\Database\Seeders\AdminMenuSeeder::class);
        
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get(route('admin.users.edit', $admin));
        
        $response->assertStatus(200);
        $response->assertViewHas('adminMenus', function ($menus) {
            return $menus->contains('label', 'Users');
        });
    }
}
