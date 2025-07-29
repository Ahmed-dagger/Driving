<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;

class ChatController extends Controller
{
    // Get or create a conversation between two users
    public function getOrCreateConversation(Request $request)
    {
        $conversation = Conversation::firstOrCreate([
            'user_one_id' => min($request->user_id, $request->other_user_id),
            'user_two_id' => max($request->user_id, $request->other_user_id),
        ]);

        return response()->json($conversation);
    }

    // Send a message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'sender_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create($request->only('conversation_id', 'sender_id', 'message'));

        return response()->json($message);
    }

    // Get all messages for a conversation
    public function getMessages($conversation_id)
    {
        $messages = Message::where('conversation_id', $conversation_id)
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    // Get new messages since last message ID
    public function getNewMessages(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'last_message_id' => 'required|integer',
        ]);

        $messages = Message::where('conversation_id', $request->conversation_id)
            ->where('id', '>', $request->last_message_id)
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }
}
