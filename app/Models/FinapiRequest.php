<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinapiRequest extends Model
{
    protected $table = 'finapi_requests';

    protected $fillable = [
        'endpoint',
        'headers',
        'payload',
        'response_code',
        'response_body',
        'request_id',
    ];
}
