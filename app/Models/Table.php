<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    // Define the table name (optional if it follows the default convention)
    protected $table = 'tables';

    // Define the fillable fields (for mass assignment)
    protected $fillable = [
        'table_number',
        'qr_code',
        'status',
        'token',
    ];

}
