<?php

namespace Uasoft\Badaso\Helpers\Firebase;

use GuzzleHttp\Client;
use Uasoft\Badaso\Models\DataType;
use Uasoft\Badaso\Models\FirebaseCloudMessages;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Models\UserRole;

class FCMNotification
{
    public static $FIREBASE_URL_API = "https://fcm.googleapis.com/fcm/send";
    public static $NAME_TABLE_DATA_TYPES = "data_types";
    public static $MAX_MEMBER_GROUP_TOKEN_MESSAGE = 999;

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
        $this->firebase_server_key = \env('MIX_FIREBASE_SERVER_KEY');
        $this->client = new Client();
        $this->priority = \config('firebase.priority');
        $this->tell_role_names = \config('firebase.tell_role_names');
    }

    /**
     * @param array $ids
     * @param string $title
     * @param string $body
     * @param array $data
     * 
     */
    public function send(array $ids = [], string $title = '', string $body = '', $data): void
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

        $this->response = $this->client->post(self::$FIREBASE_URL_API, [
            'json' => $data_json_params,
            'headers' => [
                'Authorization' => "Bearer {$this->firebase_server_key}",
                'Content-Type' => 'application/json',
            ],
        ]);
        $this->response = json_decode($this->response->getBody(), true);
    }

    /**
     * @param string $active_event
     * @param string $table_name
     * @param string $title
     * @param string $body
     * @param array $data
     */
    public function notificationEvent(string $active_event, string $table_name, string $title = '', string $body = '', $data): void
    {
        // get table data_types
        $data_type = DataType::where('name', $table_name)->first();
        if (isset($data_type)) {
            // get event list on table data_types
            $on_event_list = (array) json_decode($data_type->notification, true);
            if (in_array($active_event, $on_event_list)) {

                $token_get_messages = User::select('firebase_cloud_messages.token_get_message')
                    ->join('firebase_cloud_messages', 'firebase_cloud_messages.user_id', '=', 'users.id')
                    ->join('user_roles', 'user_roles.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                    ->whereIn('roles.name', $this->tell_role_names);

                $user = auth()->user();
                if (isset($user)) {
                    $user_id = $user->id;
                    $token_get_messages = $token_get_messages->where('users.id', '!=', $user_id);
                }

                $token_get_messages = $token_get_messages->get()
                    ->map(function ($token, $index) {
                        return $token['token_get_message'];
                    })->toArray();

                $group_token_get_messages = array_chunk($token_get_messages, self::$MAX_MEMBER_GROUP_TOKEN_MESSAGE);
                foreach ($group_token_get_messages as $key => $group_token_get_message) {
                    $this->send($group_token_get_message, $title, $body, $data);
                }
            }
        }
    }

    /**
     * @param string $active_event
     * @param string $table_name
     * @param string $title
     * @param string $body
     * @param array $data 
     */
    public static function notification(string $active_event, string $table_name, string $title = '', string $body = '', $data = [])
    {
        $fcm = new self();
        $fcm->notificationEvent($active_event, $table_name, $title, $body, $data);
    }
}
