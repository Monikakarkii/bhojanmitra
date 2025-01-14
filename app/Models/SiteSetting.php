<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    // Define the table name if it is not the default (optional)
    protected $table = 'site_settings';

    // Enable mass assignment for these fields
    protected $fillable = [
        'app_name',
        'app_logo',
        'social_links',
        'quote',
        'location',
        'contact_number',
        'contact_email',
        'theme_color_primary',
        'theme_color_secondary',
    ];

    // If you want to cast attributes like JSON
    protected $casts = [
        'social_links' => 'array',  // Assuming social links is a JSON field
    ];

}
