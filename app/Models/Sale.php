<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'total_amount',
        'payment_method',
        'completed_at',
    ];
    protected $table = 'sales';

    // Define relationship with the Order model (if it exists)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
