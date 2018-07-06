<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivateMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'conversation_id',
        'message',
        'is_read'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'read_at'
    ];

    protected $casts = [
        'is_read'   => 'boolean'
    ];

    public function author() : BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function conversation() : BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
}
