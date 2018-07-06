<?php

namespace App\Transformers;

use App\Models\Conversation;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;

class ConversationTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'lastMessage'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Conversation $conversation)
    {
        $isDynamic = $conversation->participants_count == 2;
        if ($isDynamic) {
            $receiver  = $conversation->participants->whereNotIn('id', [Auth::id()])->first();
            $conversation->name = $receiver->name;
            $conversation->image = Gravatar::get($receiver->email);
        }

        return [
            'id'            => $conversation->id,
            'name'          => $conversation->name,
            'avatar_url'    => $conversation->image,
        ];
    }

    public function includeLastMessage(Conversation $conversation)
    {
        $lastMessage = $conversation->messages->first();

        return $lastMessage ? $this->item($lastMessage, new PrivateMessageTransformer()) : $this->null();
    }
}
