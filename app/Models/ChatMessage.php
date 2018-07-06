<?php

namespace App\Models;

use App\Events\ChatMessageCreated;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ChatMessage
 *
 * @property int $id
 * @property int $user_id
 * @property int $chat_room_id
 * @property string $text
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\ChatRoom $room
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatMessage whereChatRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatMessage whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChatMessage whereUserId($value)
 */
class ChatMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => ChatMessageCreated::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function room()
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }
}
