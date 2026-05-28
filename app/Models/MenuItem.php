<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    protected $fillable = [
        'label',
        'route_name',
        'route_wildcard',
        'url',
        'icon',
        'type',
        'parent_id',
        'sort_order',
        'is_active',
        'params'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'params' => 'array',
    ];

    /**
     * Parent menu item
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Child menu items
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Scope: Only active menu items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by menu type (user, admin, frontend)
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get URL for this menu item
     */
    public function getLinkAttribute()
    {
        if ($this->route_name && Route::has($this->route_name)) {
            return route($this->route_name, $this->params ?? []);
        }

        return $this->url ?? '#';
    }
}
