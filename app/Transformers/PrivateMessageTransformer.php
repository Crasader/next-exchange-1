<?php

namespace App\Transformers;

use App\Models\PrivateMessage;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;

class PrivateMessageTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'author',
        'receiver'
    ];

    /**
     * A Fractal transformer.
     *
     * @param PrivateMessage $message
     * @return array
     */
    public function transform(PrivateMessage $message)
    {
        $isMy = $message->author->id === Auth::id();

        return [
            'id'            => $message->id,
            'message'       => $message->message,
            'created_at'    => $message->created_at,
            'is_read'       => $message->is_read,
            'read_at'       => $message->read_at,
            'humans_date'   => $message->created_at->diffForHumans(),
            'is_my'         => $isMy
        ];
    }

    public function includeAuthor(PrivateMessage $message)
    {
        return is_null($message->author_id) ? $this->null() : $this->item($message->author, new UserTransformer());
    }

    public function includeReceiver(PrivateMessage $message)
    {
        return is_null($message->receiver_id) ? $this->null() : $this->item($message->receiver, new UserTransformer());
    }
}
