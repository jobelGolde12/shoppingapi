<?php

use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/conversation/start', [MessageController::class, 'startConversation']); // Begins a new chat
Route::post('/message/send', [MessageController::class, 'sendMessage']); // Posts a message
Route::get('/message/{conversation_id}', [MessageController::class, 'getMessages']); // Gets chat history
Route::put('/message/{id}', [MessageController::class, 'updateMessage']); // Edits a message
Route::delete('/message/{id}', [MessageController::class, 'deleteMessage']); // Removes a message
Route::put('/message/{id}/read', [MessageController::class, 'markAsRead']); // Flags as seen
Route::get('/conversations/{id}', [MessageController::class, 'myConversations']); // Lists user's chats
