<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderService extends Model
{
    /** @use HasFactory<\Database\Factories\OrderServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'orderId',
        'serviceId'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
