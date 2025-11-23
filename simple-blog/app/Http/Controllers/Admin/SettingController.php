<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_title' => Setting::get('site_title', 'My Blog'),
            'site_description' => Setting::get('site_description', 'A simple blog built with Laravel'),
            'site_keywords' => Setting::get('site_keywords', 'blog, laravel, articles'),
            'meta_author' => Setting::get('meta_author', ''),
            'meta_og_image' => Setting::get('meta_og_image', ''),
            'footer_text' => Setting::get('footer_text', 'All rights reserved.'),
            'posts_per_page' => Setting::get('posts_per_page', 10),
            'hero_show' => Setting::get('hero_show', '1'),
            'hero_title' => Setting::get('hero_title', 'Stay curious.'),
            'hero_description' => Setting::get('hero_description', ''),
            'coins_per_post' => Setting::get('coins_per_post', 10),
            'coins_per_1000_views' => Setting::get('coins_per_1000_views', 5),
            'monetization_min_posts' => Setting::get('monetization_min_posts', 3),
            'monetization_min_views' => Setting::get('monetization_min_views', 100),
            'enable_seo_analyzer' => Setting::get('enable_seo_analyzer', '1'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:255',
            'meta_author' => 'nullable|string|max:255',
            'meta_og_image' => 'nullable|url|max:500',
            'footer_text' => 'nullable|string|max:255',
            'posts_per_page' => 'required|integer|min:1|max:100',
            'hero_show' => 'nullable|boolean',
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string|max:500',
            'coins_per_post' => 'required|numeric|min:0',
            'coins_per_1000_views' => 'required|numeric|min:0',
            'monetization_min_posts' => 'required|integer|min:0',
            'monetization_min_views' => 'required|integer|min:0',
        ]);

        $data = $request->except('_token');
        // Handle checkboxes: if present it's '1', if missing it's '0'
        $data['hero_show'] = $request->has('hero_show') ? '1' : '0';
        $data['enable_seo_analyzer'] = $request->has('enable_seo_analyzer') ? '1' : '0';

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
