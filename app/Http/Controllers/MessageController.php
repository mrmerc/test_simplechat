<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Events\MessageSent;

class MessageController extends Controller
{
    /**
	 * Send message to chat
	 */
	public function sendMessage(Request $request) {

		$data = json_decode($request->getContent(), true);

		$result = Message::createRedisRecord( $data );

		broadcast(new MessageSent( json_encode($result) ))->toOthers();
	}

	/**
	 * Get message history from Redis
	 */
	public function getMessageHistory() {

		$messages = Message::getRedisRecords();
		$messages = array_reverse($messages);
		
		return response()->json([
			'history' => $messages,
		]);
	}
}
