<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'total',
        'discount',
        'final_total',
        'status',
        'payment_method',
        'notes',
        'sale_date',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_total' => 'decimal:2',
        'sale_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}