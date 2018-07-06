<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChatRoom
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ChatMessage[] $messages
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatRoom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatRoom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatRoom whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatRoom whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatRoom whereUpdatedAt($value)
 */
class ChatRoom extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->HasMany(ChatMessage::class);
    }
}