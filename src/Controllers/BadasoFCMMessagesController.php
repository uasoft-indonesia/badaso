<?php

namespace Uasoft\Badaso\Controllers;

use App\FCMMessage;
use Illuminate\Http\Request;
use Uasoft\Badaso\Controllers\Controller;
use Uasoft\Badaso\Helpers\ApiResponse;

class BadasoFCMMessagesController extends Controller
{
    public function getMessages()
    {
        try {
            $user = auth()->user();
            $fcm_messages = FCMMessage::where('receiver_user_id', $user->id)->get();
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
}
