<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Start a new conversation or return existing one
    public function startConversation(Request $request)
    {
        try {
            $request->validate([
                'user_one_id' => 'required|integer',
                'user_two_id' => 'required|integer',
            ]);

            // Check if conversation already exists
            $conversation = Conversation::where(function($q) use ($request) {
                $q->where('user_one_id', $request->user_one_id)
                ->where('user_two_id', $request->user_two_id);
            })->orWhere(function($q) use ($request) {
                $q->where('user_one_id', $request->user_two_id)
                ->where('user_two_id', $request->user_one_id);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create($request->only('user_one_id', 'user_two_id'));
            }

            return response()->json([
                'success' => true,
                'message' => 'Conversation fetched/created successfully',
                'data' => $conversation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Send a message
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'required|integer',
                'sender_id'       => 'required|integer',
                'message'         => 'required|string',
            ]);

            $message = Message::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get messages in a conversation
    public function getMessages($conversation_id)
    {
        try {
            $messages = Message::where('conversation_id', $conversation_id)
                                ->orderBy('created_at', 'ASC')
                                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Messages fetched successfully',
                'data' => $messages
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update a message
    public function updateMessage(Request $request, $id)
    {
        try {
            $request->validate([
                'message' => 'required|string'
            ]);

            $message = Message::findOrFail($id);
            $message->update(['message' => $request->message]);

            return response()->json([
                'success' => true,
                'message' => 'Message updated successfully',
                'data' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete a message
    public function deleteMessage($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();

            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully',
                'data' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Mark a message as read
    public function markAsRead($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Message marked as read successfully',
                'data' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // List conversations for a specific user
    public function myConversations($user_id)
    {
        try {
            $conversations = Conversation::where('user_one_id', $user_id)
                ->orWhere('user_two_id', $user_id)
                ->with(['messages' => function($q) {
                    $q->orderBy('created_at', 'DESC');
                }])
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Conversations fetched successfully',
                'data' => $conversations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
