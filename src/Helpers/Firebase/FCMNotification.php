<?php

namespace Uasoft\Badaso\Helpers\Firebase;

use GuzzleHttp\Client;
use Uasoft\Badaso\Models\DataType;
use Uasoft\Badaso\Models\Notification;
use Uasoft\Badaso\Models\User;

class FCMNotification
{
    public static $FIREBASE_URL_API = 'https://fcm.googleapis.com/fcm/send';
    public static $NAME_TABLE_DATA_TYPES = 'data_types';
    public static $TYPE_MESSAGES = 'notification';

    public static $ACTIVE_EVENT_ON_CREATE = 'onCreate';
    public static $ACTIVE_EVENT_ON_UPDATE = 'onUpdate';
    public static $ACTIVE_EVENT_ON_READ = 'onRead';
    public static $ACTIVE_EVENT_ON_DELETE = 'onDelete';

    public $client;
    public $firebase_server_key;
    public $response;
    public $priority;
    public $tell_role_names;

    public function __construct()
    {
        $this->firebase_server_key = \env('MIX_FIREBASE_SERVER_KEY', '');
        $this->client = new Client();
        $this->priority = \config('firebase.priority');
        $this->tell_role_names = \config('firebase.tell_role_names');
    }

    /**
     * @param  array  $data
     */
    protected function send(array $ids = [], string $title = '', string $body = '', $data = []): void
    {
        $data_json_params = [
            'registration_ids' => $ids,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'priority' => $this->priority,
        ];

        if (isset($data)) {
            $data_json_params['data'] = $data;
        }

        if (count($ids) > 0) {
            $this->response = $this->client->post(self::$FIREBASE_URL_API, [
                'json' => $data_json_params,
                'headers' => [
                    'Authorization' => "Bearer {$this->firebase_server_key}",
                    'Content-Type' => 'application/json',
                ],
            ]);
            $this->response = json_decode($this->response->getBody(), true);
        }
    }

    /**
     * @param  array  $data
     */
    protected function notificationEvent(string $active_event, string $table_name, string $title = '', string $body = '', $data = []): void
    {
        // get table data_types
        $data_type = DataType::where('name', $table_name)->first();
        if (isset($data_type)) {
            // get event list on table data_types
            $on_event = collect(json_decode($data_type->notification, true))->where('event', $active_event)->first();

            $notification_message_title = $on_event['notification_message_title'];
            if ($notification_message_title != null && $notification_message_title != '') {
                $title = $notification_message_title;
            }
            $notification_message = $on_event['notification_message'];
            if ($notification_message != null && $notification_message != '') {
                $body = $notification_message;
            }

            if (isset($on_event)) {
                $user_get_messages = User::select('firebase_cloud_messages.token_get_message', 'user_roles.user_id')
                    ->join('firebase_cloud_messages', 'firebase_cloud_messages.user_id', '=', 'users.id')
                    ->join('user_roles', 'user_roles.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                    ->whereIn('roles.name', $this->tell_role_names);

                $user = auth()->user();
                if (isset($user)) {
                    $user_id = $user->id;
                    $user_get_messages = $user_get_messages->where('users.id', '!=', $user_id);
                }

                $user_get_messages = $user_get_messages->get();

                if (isset($user)) {
                    $datetime_now = date('Y-m-d H:i:s');
                    foreach ($user_get_messages as $key => $value) {
                        $user_get_token_messages = $value['token_get_message'];
                        $fmc_messages = [
                            'receiver_user_id' => $value['user_id'],
                            'type' => self::$TYPE_MESSAGES,
                            'title' => $title,
                            'content' => $body,
                            'is_read' => false,
                            'sender_user_id' => $user->id,
                            'created_at' => $datetime_now,
                            'updated_at' => $datetime_now,
                        ];
                        $fmc_messages_model = Notification::create($fmc_messages);
                        $data['fcm_message_id'] = $fmc_messages_model->id;
                        $this->send([$user_get_token_messages], $title, $body, $data);
                    }
                }
            }
        }
    }

    /**
     * @param  string  $title
     * @param  string  $body
     * @param  array  $data
     */
    public static function notification(string $active_event, string $table_name, string $title = null, string $body = null, $data = [])
    {
        try {
            $user_name = 'user';
            $user = auth()->user();
            if (isset($user)) {
                $user_name = $user->name;
                $data['user_name'] = $user_name;
            }

            $active_event_lowercase_mode = strtolower($active_event);

            if ($title == null) {
                $title = __("badaso::notification.event_table.{$active_event_lowercase_mode}.title", [
                    'table_name' => $table_name,
                ]);
            }

            if ($body == null) {
                $body = __("badaso::notification.event_table.{$active_event_lowercase_mode}.body", [
                    'table_name' => $table_name,
                    'user_name' => $user_name,
                ]);
            }

            $fcm = new self();
            if (count($data) > 0) {
                $fcm->notificationEvent($active_event, $table_name, $title, $body, $data);
            }
        } catch (\Exception $e) {
            //throw $th;
        }
    }
}
