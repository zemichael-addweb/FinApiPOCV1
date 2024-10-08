<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinapiPaymentRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'iban', 
        'bic', 
        'bank_name', 
        'street', 
        'house_number', 
        'post_code', 
        'city', 
        'country'
    ];
}
