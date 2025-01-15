<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    // Fillable attributes
    protected $fillable = [
        'name',
        'description',
        'slug',
        'status',
        'icon',
        'show_on_nav',
        'show_on_home',
        'nav_index',
        'home_index',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug before creating the category
        static::creating(function ($category) {
            $category->slug = $category->slug ?? Str::slug($category->name);
        });

        // Auto-update slug before updating the category
        static::updating(function ($category) {
            $category->slug = $category->slug ?? Str::slug($category->name);
        });
    }
    // Many-to-many relationship with menu items
    // public function menuItems()
    // {
    //     return $this->belongsToMany(MenuItem::class, 'category_menu_item');
    // }
}
