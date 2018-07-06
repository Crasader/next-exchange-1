<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Transformers\ConversationTransformer;
use Illuminate\Http\Request;
use App\Models\PrivateMessage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;
use App\Transformers\PrivateMessageTransformer;
use App\Notifications\PrivateMessageCreatedNotification;
use App\Http\Requests\PrivateMessaging\SendPrivateMessageRequest;

class PrivateMessagesController extends Controller
{
    /**
     * Reads unread messages
     * @deprecated TODO: Create better arch
     * @param AbstractPaginator|Collection $messages
     */
    protected function readUnreadMessages (&$messages)
    {
        $now = Carbon::now();

        $messages = $messages instanceof AbstractPaginator ? $messages->getCollection() : $messages;

        $unreadMessages = $messages->filter(function (PrivateMessage $message) use ($now) {
            if ($message->is_read) return false;
            $message->read_at = $now;
            $message->is_read = true;
            return true;
        });

        PrivateMessage::whereIn('id', $unreadMessages->pluck('id'))
            ->update(['read_at' => $now, 'is_read' => true]);

    }

    /**
     * Fetches messaging history with certain user
     * and mark them as read messages
     *
     * @param Request $request
     * @return \Spatie\Fractal\Fractal
     */
    public function fetchHistory(Request $request)
    {
        $messages = PrivateMessage::where('conversation_id', $request->conversation_id)
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return transformModel($messages, new PrivateMessageTransformer(), [], ['author'])
            ->respond();
    }

    /**
     * Create and send private message to it's recipient
     * through broadcast channel
     *
     * @param SendPrivateMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(SendPrivateMessageRequest $request)
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::find($request->conversation_id);

        $message = PrivateMessage::create([
            'author_id'         => Auth::id(),
            'conversation_id'   => $conversation->id,
            'message'           => $request->message
        ]);

        $data = transformModel($message, new PrivateMessageTransformer(), [], ['author']);

        $receiversIds = $conversation->participants()
            ->where('user_id', '!=', Auth::id())
            ->get();

        Notification::send($receiversIds, new PrivateMessageCreatedNotification($data->toArray()));

        return $data->respond(Response::HTTP_CREATED);
    }

    /**
     * List user conversations
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listConversations(Request $request)
    {
        /** @var LengthAwarePaginator $conversations */
        $conversations = Auth::user()
            ->conversations()
            ->withCount(['participants'])
            ->with(['messages' => function($builder) {
                $builder->orderBy('created_at', 'desc')
                    ->take(1);
            }])
            ->get();

        $globals = Conversation::where('is_global', true)->get();

        $globals->map(function (Conversation $global) use (&$conversations) {
            $conversations->prepend($global);
        });

        return transformModel($conversations, new ConversationTransformer(), [], ['lastMessage'])
            ->respond();
    }
}
