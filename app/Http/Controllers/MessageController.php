<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{

    /* ============================================================
       FORMAT IF EITHER JSON OR XML
    ============================================================ */
    private function isJsonOrXml($data, $status = 200)
{
    $acceptHeader = strtolower(request()->header('Accept', ''));
    $formatParam = strtolower(request()->get('format', ''));

    // ALWAYS honor ?format=xml first
    if ($formatParam === 'xml') {
        return $this->convertToXml($data, $status);
    }

    if ($formatParam === 'json') {
        return response()->json($data, $status);
    }

    // Next, check Accept headers
    if (str_contains($acceptHeader, 'application/xml') ||
        str_contains($acceptHeader, 'text/xml') ||
        str_contains($acceptHeader, 'xml')) {
        return $this->convertToXml($data, $status);
    }

    if (str_contains($acceptHeader, 'application/json') ||
        str_contains($acceptHeader, 'json')) {
        return response()->json($data, $status);
    }

    // Default to JSON
    return response()->json($data, $status);
}



    private function convertToXml($data, $status = 200)
{
    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response/>');

    $this->arrayToXml($data, $xml);

    return response($xml->asXML(), $status)
        ->header('Content-Type', 'application/xml');
}

private function arrayToXml($data, &$xml)
{
    foreach ($data as $key => $value) {

        // Numeric keys → use a generic tag name
        if (is_numeric($key)) {
            $key = "item_" . $key;
        }

        if (is_array($value)) {
            $subnode = $xml->addChild($key);
            $this->arrayToXml($value, $subnode);

        } elseif (is_object($value)) {
            $subnode = $xml->addChild($key);
            $this->arrayToXml((array)$value, $subnode);

        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
}


    private function sanitizeXmlKey($key)
    {
        if (empty($key)) return 'item';

        $key = preg_replace('/[^a-zA-Z0-9_]/', '_', $key);

        if (empty($key) || is_numeric(substr($key, 0, 1))) {
            $key = 'item_' . $key;
        }

        return $key;
    }


    /* ============================================================
       MESSAGE FUNCTIONS
    ============================================================ */

    // Start or fetch conversation
    public function startConversation(Request $request)
    {
        try {
            $request->validate([
                'user_one_id' => 'required|integer',
                'user_two_id' => 'required|integer',
            ]);

            $conversation = Conversation::where(function($q) use ($request) {
                $q->where('user_one_id', $request->user_one_id)
                  ->where('user_two_id', $request->user_two_id);
            })->orWhere(function($q) use ($request) {
                $q->where('user_one_id', $request->user_two_id)
                  ->where('user_two_id', $request->user_one_id);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'user_one_id' => $request->user_one_id,
                    'user_two_id' => $request->user_two_id
                ]);
            }

            return $this->isJsonOrXml([
                'success' => true,
                'message' => 'Conversation fetched/created successfully',
                'data' => $conversation->toArray()
            ]);

        } catch (\Exception $e) {
            return $this->isJsonOrXml([
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
                'sender_id' => 'required|integer',
                'message' => 'required|string',
            ]);

            $message = Message::create($request->all());

            return $this->isJsonOrXml([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message->toArray()
            ], 201);

        } catch (\Exception $e) {
            return $this->isJsonOrXml([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get conversation messages
    public function getMessages($conversation_id)
    {
        try {
            $messages = Message::where('conversation_id', $conversation_id)
                ->orderBy('created_at', 'ASC')
                ->get();

            return $this->isJsonOrXml([
                'success' => true,
                'message' => 'Messages fetched successfully',
                'data' => $messages->toArray()
            ]);

        } catch (\Exception $e) {
            return $this->isJsonOrXml([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update a message
    public function updateMessage(Request $request, $id)
    {
        try {
            $request->validate(['message' => 'required|string']);

            $message = Message::findOrFail($id);
            $message->update(['message' => $request->message]);

            return $this->isJsonOrXml([
                'success' => true,
                'message' => 'Message updated successfully',
                'data' => $message->toArray()
            ]);

        } catch (\Exception $e) {
            return $this->isJsonOrXml([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete message
    public function deleteMessage($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();

            return $this->isJsonOrXml([
                'success' => true,
                'message' => 'Message deleted successfully',
                'deleted_id' => $id
            ]);

        } catch (\Exception $e) {
            return $this->isJsonOrXml([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Mark as read
    public function markAsRead($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->update(['is_read' => true]);

            return $this->isJsonOrXml([
                'success' => true,
                'message' => 'Message marked as read',
                'data' => $message->toArray()
            ]);

        } catch (\Exception $e) {
            return $this->isJsonOrXml([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // List conversations for a user
    public function myConversations($user_id)
    {
        Log::info("Fetching conversations for user_id: $user_id");
        try {
            $conversations = Conversation::where('user_one_id', $user_id)
                ->orWhere('user_two_id', $user_id)
                ->with(['messages' => function($q){
                    $q->orderBy('created_at', 'DESC');
                }])
                ->get();

            return $this->isJsonOrXml([
                'success' => true,
                'message' => 'Conversations fetched successfully',
                'data' => $conversations->toArray()
            ]);

        } catch (\Exception $e) {
            return $this->isJsonOrXml([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
