<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_settings()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.index');
    }

    public function test_admin_can_update_settings()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'site_title' => 'New Site Title',
            'site_description' => 'New Description',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        
        $this->assertEquals('New Site Title', Setting::get('site_title'));
        $this->assertEquals('New Description', Setting::get('site_description'));
    }
}
