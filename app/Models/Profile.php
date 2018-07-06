<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_details';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * Fillable fields for a Profile
     *
     * @var array
     */
    protected $fillable = [
        'fullname',
        'occupation',
        'company',
        'phone',
        'address',
        'state',
        'city',
        'postcode',
        'country',
        'bitcoin',
        'ether',
        'litecoin',
        'facebbook',
        'linkedin',
        'twitter',
        'instagram' .
        //'location',
        //'bio',
        //'twitter_username',
        //'github_username',
        //'user_profile_bg',
        //'avatar',
        //'avatar_status',
        'slug',
    ];

    protected $casts = [

    ];

    /**
     * A profile belongs to a user
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Profile Theme Relationships.
     *
     * @var array
     */
    public function theme()
    {
        return $this->hasOne('App\Models\Theme');
    }
}
