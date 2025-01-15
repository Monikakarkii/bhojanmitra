<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Tag extends Model
{
    protected $fillable = ['name'];

    // Relationship to MenuItem (many-to-many)
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class);
    }
}
