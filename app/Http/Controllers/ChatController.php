<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatMessageRequest;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Auth;
use Illuminate\Http\Request;
use Response;

class ChatController extends Controller
{
    /**
     * @param ChatMessageRequest $request
     * @param $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function postMessage(ChatMessageRequest $request, $room)
    {
        $room = ChatRoom::findOrFail($room);
        $user = Auth::user();

        $message = new ChatMessage($request->validated());
        $message->user()->associate($user);
        $message->room()->associate($room);

        if($message->save()) {
            return Response::json(['status' => 'ok']);
        } else {
            return Response::json(['status' => 'fail'], 422);
        }
    }

    /**
     * @param Request $request
     * @param $room
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request, $room)
    {
        /** @var ChatRoom $room */
        $room = ChatRoom::findOrFail($room);

        return Response::json($room->messages()->with('user')
            ->orderBy('id', 'desc')
            ->limit($request->get('limit', 10))
            ->offset($request->get('offset', 0))
            ->get());
    }

    /**
     * @param $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoom($name) {
        return Response::json(ChatRoom::whereName($name)->firstOrFail());
    }
}