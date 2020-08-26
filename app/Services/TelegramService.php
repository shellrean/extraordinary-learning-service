<?php

namespace App\Services;

class TelegramService
{
	/**
	 * Send task to classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public static function sendNotifTask($task, $telegram_id)
	{
		try {
			\Telegram::sendMessage([
	            'chat_id' => $telegram_id, 
	            'text' => "<b>".$task->task->title."</b>\n\n".$task->body."\n\nBatas:\n".$task->task->deadline."\n\n".$task->user->name."\n#Tugas",
	                'parse_mode' => "html"
	        ]);
		} catch (\Exception $e) {
			
		}
	}

	/**
	 * Send lecture to classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public static function sendNotifLecture($lecture, $telegram_id)
	{
		try {
			\Telegram::sendMessage([
	            'chat_id' => $telegram_id, 
	            'text' => "<b>".$lecture->lecture->title."</b>\n\n".$lecture->body."\n\n".$lecture->teacher->name."\n#Materi\n#".str_replace(" ", '', $lecture->lecture->subject->name),
	                'parse_mode' => "html"
	        ]);
		} catch (\Exception $e) {
			
		}
	}
}