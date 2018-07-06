<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Srmklive\Authy\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatableContract;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Helper;

class User extends Authenticatable implements TwoFactorAuthenticatableContract
{
    use Notifiable;
    use HasRoleAndPermission; // To bring in the assignrole functions
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_country_code',
        'phone_number',
        'referred_by',
        'token',
        'activated',
        'signup_ip_address',
        'signup_confirmation_ip_address',
        'signup_sm_ip_address',
        'admin_ip_address',
        'updated_ip_address',
        'deleted_ip_address',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_options',
        'activated',
        'token'
    ];

    protected $dates = [
        'deleted_at'
    ];

    //public function referrals() {
        //return $this->belongsToMany('App\User');
    //
    //
    //}

    public function getReferral($user, $program) {
        return url('https://next.exchange?ref='.$this->uri);
    }


    public function wallet() {
        return $this->hasMany(Wallet::class);
    }

    public function social()
    {
        //return $this->hasMany('App\Models\Social');
    }

    /**
     * User Profile Relationships
     *
     * @var array
     */
    public function profile()
    {
        return $this->hasOne('App\Models\Profile');
    }
    // User Profile Setup - SHould move these to a trait or interface...

    public function profiles()
    {
        return $this->belongsToMany('App\Models\Profile')->withTimestamps();
    }

    public function hasProfile($name)
    {
        foreach($this->profiles as $profile)
        {
            if($profile->name == $name) return true;
        }
        return false;
    }

    public function assignProfile($profile)
    {
        return $this->profiles()->attach($profile);
    }

    public function removeProfile($profile)
    {
        return $this->profiles()->detach($profile);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTokenHolder() {
       return $this->access()->where('active', '=', 1)->first();
    }

    /*
     * ACCESS
     */

    public function access()
    {
        return $this->hasOne(\App\Models\Access::class);
    }

    /*
     * User ip's
     */
    public function user_ips()
    {
        return $this->hasOne(UsersIp::class);
    }


    public function likes() : BelongsToMany
    {
        return $this->belongsToMany(User::class,'users_likes', 'user_id', 'liked_user_id');
    }

    public function followers() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_follows', 'user_id', 'follower_user_id');
    }

    public function connections() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_connections', 'user_id', 'connected_user_id')
            ->withPivot(['status']);
    }

    public function conversations() : BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversations_has_users', 'user_id', 'conversation_id');
    }

    public function sentMessages() : HasMany
    {
        return $this->hasMany(PrivateMessage::class, 'author_id');
    }

    public function acceptedConnections() : BelongsToMany
    {
        return $this->connections()->where('status', 'accepted');
    }

    public function pendingConnections() : BelongsToMany
    {
        return $this->connections()->where('status', 'pending');
    }

    public function articles() : HasMany
    {
        return $this->hasMany(Article::class, 'user_id');
    }

    public function articlesComments() : HasManyThrough
    {
        return $this->hasManyThrough(
            ArticleComment::class,
            Article::class,
            'user_id',
            'article_id'
        );
    }

    public function articlesLikes() : BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'articles_has_likes', 'user_id', 'article_id');
    }

    /**
     * Return ICO's user participating in
     */
    public function projects() : BelongsToMany
    {
        return $this->belongsToMany(Ico::class, 'ico_members', 'user_id', 'ico_id')
            ->withPivot(['created_at']);
    }

    public function projectRoles() : BelongsToMany
    {
        return $this->belongsToMany(IcoRole::class, 'users_has_icos_roles', 'user_id', 'ico_role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chatMessages() {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return config('services.slack.webhook_url');
    }
}
