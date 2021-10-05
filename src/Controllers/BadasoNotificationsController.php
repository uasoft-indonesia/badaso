<?php

namespace Uasoft\Badaso\Controllers;

use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Models\Notification;

class BadasoNotificationsController extends Controller
{
    public function getMessages()
    {
        try {
            $user = auth()->user();
            $fcm_messages = Notification::where('receiver_user_id', $user->id)->orderBy('created_at', 'desc')->get();
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
            $fcm_messages = Notification::where('id', $id)->first();
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

    public function getCountUnreadMessage()
    {
        try {
            $user = auth()->user();
            $user_id = $user->id;
            $fcm_messages = Notification::where('receiver_user_id', $user_id)->where('is_read', true)->count();

            return ApiResponse::success([
                'count_unread_message' => $fcm_messages,
            ]);
        } catch (\Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
