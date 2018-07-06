<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Transformers\ConversationTransformer;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\PrivateMessaging\CreateConversationRequest;

class ConversationController extends Controller
{
    public function create(CreateConversationRequest $request)
    {
        $conversation = new Conversation([
           'name'   => 'Test'
        ]);
        $conversation->creator()->associate(Auth::user());
        $conversation->save();

        $conversation->participants()->attach([$request->user_id, Auth::id()]);

        return transformModel($conversation, new ConversationTransformer())
            ->respond(Response::HTTP_CREATED);
    }
}
