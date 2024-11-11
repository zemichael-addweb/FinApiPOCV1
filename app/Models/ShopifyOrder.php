<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyOrder extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shopify_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shopify_id',
        'name',
        'email',
        'processed_at',
        'data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'processed_at' => 'datetime',
        'data' => 'json',
    ];
}
