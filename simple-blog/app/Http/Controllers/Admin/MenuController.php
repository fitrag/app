<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('children')->parents()->ordered()->get();
        $parentMenus = Menu::parents()->ordered()->get();
        return view('admin.menus.index', compact('menus', 'parentMenus'));
    }

    public function create()
    {
        $parentMenus = Menu::parents()->ordered()->get();
        return view('admin.menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer|min:0',
            'location' => 'required|string|in:public,admin',
        ]);

        $menu = Menu::create([
            'parent_id' => $request->parent_id,
            'label' => $request->label,
            'url' => $request->url,
            'order' => $request->order,
            'location' => $request->location,
            'is_active' => $request->has('is_active'),
            'open_new_tab' => $request->has('open_new_tab'),
        ]);

        // Auto-assign permission to the creator (current admin)
        auth()->user()->menus()->attach($menu->id);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parentMenus = Menu::parents()->where('id', '!=', $menu->id)->ordered()->get();
        return view('admin.menus.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'required|integer|min:0',
            'location' => 'required|string|in:public,admin',
        ]);

        $menu->update([
            'parent_id' => $request->parent_id,
            'label' => $request->label,
            'url' => $request->url,
            'order' => $request->order,
            'location' => $request->location,
            'is_active' => $request->has('is_active'),
            'open_new_tab' => $request->has('open_new_tab'),
        ]);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu item deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        foreach ($request->items as $order => $item) {
            Menu::where('id', $item['id'])->update([
                'order' => $order,
                'parent_id' => $item['parent_id'] ?? null,
            ]);
            
            // Update children if exists
            if (isset($item['children'])) {
                foreach ($item['children'] as $childOrder => $child) {
                    Menu::where('id', $child['id'])->update([
                        'order' => $childOrder,
                        'parent_id' => $item['id'],
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }
}
