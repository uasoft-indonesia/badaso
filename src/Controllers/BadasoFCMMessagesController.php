<?php

namespace Uasoft\Badaso\Controllers;

use Uasoft\Badaso\Controllers\Controller;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\FCMMessage;

class BadasoFCMMessagesController extends Controller
{
    public function getMessages()
    {
        try {
            $user = auth()->user();
            $fcm_messages = FCMMessage::where('receiver_user_id', $user->id)->orderBy('created_at', 'desc')->get();
            foreach ($fcm_messages as $key => $value) {
                $value->sender_users;
            }

            return ApiResponse::success([
                'messages' => $fcm_messages->toArray(),
            ]);
        } catch (\Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function readMessage($id)
    {
        try {
            $fcm_messages = FCMMessage::where('id', $id)->first();
            if (isset($fcm_messages)) {
                $fcm_messages->update([
                    'is_read' => true,
                ]);
            }

            return ApiResponse::success([
                'messages' => $fcm_messages->toArray(),
            ]);
        } catch (\Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
