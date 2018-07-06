<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ico extends Model
{
	
	protected $table = 'ico';

    protected $fillable = [
        'name', 'symbol', 'total_supply_token', 'stage', 'launch_date', 'initial_price', 'short_description', 'full_description', 'website_url', 'whitepaper_url', 'twitter_url', 'facebook_url', 'telegram_url', 'bitcointalk_url', 'official_video_url'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function members() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ico_members', 'ico_id', 'user_id');
    }

    public function roles() : HasMany
    {
        return $this->hasMany(IcoRole::class);
    }
}
