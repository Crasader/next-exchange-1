<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawToken extends Model
{
    public $timestamps = false;
    protected $table = 'withdraw_tokens';
    protected $fillable = [
        'token',
        'user_id'
    ];
    protected $dates = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
