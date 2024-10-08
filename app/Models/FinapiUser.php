<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinapiUser extends Model
{
    protected $table = 'finapi_users';

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'email',
        'access_token',
        'expire_at',
        'refresh_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
