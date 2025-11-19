<?php

use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/conversation/start', [MessageController::class, 'startConversation']);
Route::post('/message/send', [MessageController::class, 'sendMessage']);
Route::get('/message/{conversation_id}', [MessageController::class, 'getMessages']);
Route::put('/message/{id}', [MessageController::class, 'updateMessage']);
Route::delete('/message/{id}', [MessageController::class, 'deleteMessage']);
Route::put('/message/{id}/read', [MessageController::class, 'markAsRead']);
Route::get('/conversations/{user_id}', [MessageController::class, 'myConversations']);
