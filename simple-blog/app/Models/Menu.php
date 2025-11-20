<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'label',
        'url',
        'order',
        'is_active',
        'open_new_tab',
        'location',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_new_tab' => 'boolean',
    ];

    /**
     * Get the parent menu
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menus
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->ordered();
    }

    /**
     * Get all active children
     */
    public function activeChildren()
    {
        return $this->hasMany(Menu::class, 'parent_id')->active()->ordered();
    }

    /**
     * Check if menu has children
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Check if menu is a parent (top-level menu)
     */
    public function isParent()
    {
        return is_null($this->parent_id);
    }

    /**
     * Scope to get only active menus
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get menus ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope to get only parent menus (top-level)
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
    /**
     * The users that belong to the menu.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Scope to get menus by location
     */
    public function scopeLocation($query, $location)
    {
        return $query->where('location', $location);
    }
}
