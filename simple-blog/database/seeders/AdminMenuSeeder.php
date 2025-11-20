<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'label' => 'Posts',
                'url' => 'admin.posts.index',
                'order' => 1,
                'location' => 'admin',
            ],
            [
                'label' => 'Pages',
                'url' => 'admin.pages.index',
                'order' => 2,
                'location' => 'admin',
            ],
            [
                'label' => 'Categories',
                'url' => 'admin.categories.index',
                'order' => 3,
                'location' => 'admin',
            ],
            [
                'label' => 'Tags',
                'url' => 'admin.tags.index',
                'order' => 4,
                'location' => 'admin',
            ],
            [
                'label' => 'Menus',
                'url' => 'admin.menus.index',
                'order' => 5,
                'location' => 'admin',
            ],
            [
                'label' => 'Analytics',
                'url' => 'admin.analytics.index',
                'order' => 6,
                'location' => 'admin',
            ],
            [
                'label' => 'Settings',
                'url' => 'admin.settings.index',
                'order' => 7,
                'location' => 'admin',
            ],
            [
                'label' => 'Users',
                'url' => 'admin.users.index',
                'order' => 8,
                'location' => 'admin',
            ],
        ];

        foreach ($menus as $menu) {
            \App\Models\Menu::updateOrCreate(
                ['label' => $menu['label'], 'location' => 'admin'],
                $menu
            );
        }
    }
}
