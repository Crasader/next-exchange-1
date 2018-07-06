<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * The reason why this model exists is
 * it represents actual conversation,
 * which gives more flexibility
 * on making conversations
 * and sending global
 * messages
 */
class Conversation extends Model
{
    public const ONE_WAY = 'one_way';
    public const TWO_WAY = 'two_way';

    protected $fillable = [
        'image',
        'name',
        'creator_id',
        'type',
        'is_global'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_global' => 'boolean'
    ];

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function participants() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversations_has_users', 'conversation_id', 'user_id');
    }

    public function messages() : HasMany
    {
        return $this->hasMany(PrivateMessage::class, 'conversation_id');
    }
}
