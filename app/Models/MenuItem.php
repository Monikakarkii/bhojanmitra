<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    protected $fillable = ['name', 'description', 'price', 'category_id', 'availability', 'slug', 'image', 'short_description'];

    // Relationship to Category (many-to-many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_menu_item');
    }

    // Relationship to Tags (many-to-many)
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'menu_item_tags');
    }


    // Generate slug before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menuItem) {
            $menuItem->slug = Str::slug($menuItem->name);
        });

        static::updating(function ($menuItem) {
            $menuItem->slug = Str::slug($menuItem->name);
        });
    }
}
