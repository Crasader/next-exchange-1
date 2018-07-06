<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    protected $table = 'access';

    protected $fillable = [
        'user_id',
        'country',
        'etherId',
        'coin_type',
        'coin_buy',
        'coin_sell',
        'coin_amount',
        'fiat',
        'active',
    ];

    public function scopeActive($query, $id)
    {
        return $query->find($id)->update(['active' => 1]);
    }

    public function scopeInactive($query, $id)
    {
        return $query->find($id)->update(['active' => 0]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}