<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Message extends Model
{
    public static function createRedisRecord(Array $data) {

		$key = 'simplechat:messages';

		$length = Redis::zcard($key) + 1;

		$value = ':message:' . $length;

		$score = microtime(true);

		if (Redis::zadd($key, $score, $value) === 1) {
			
			$hashKey = 'simplechat' . $value;

			
			$values = array(
				'date' => $data['date'], 
				'name' => $data['name'],
				'text' => $data['text'],
				'from' => $data['from'],
			);

			Redis::hmset($hashKey, $values);

			return $values;

		} else {
			return [];
		}
	}

	public static function getRedisRecords() {

		$key = 'simplechat:messages';
		$valuesFromSet = Redis::zrange($key, 0, -1); // ['message:1', 'message:2', ...]
		$messages = [];

		foreach($valuesFromSet as $msg) {
			$hashKey = 'simplechat' . $msg;
			$hashData = Redis::hgetall($hashKey);
			
			$messages[] = $hashData;
		}

		return $messages;
	}
}
