<?php

namespace Uasoft\Badaso\Helpers\Firebase;

class FirebasePublishFile
{
    public function getContentFirebaseMessagingSwJs()
    {
        $MIX_FIREBASE_API_KEY = \env('MIX_FIREBASE_API_KEY');
        $MIX_FIREBASE_AUTH_DOMAIN = \env('MIX_FIREBASE_AUTH_DOMAIN');
        $MIX_FIREBASE_PROJECT_ID = \env('MIX_FIREBASE_PROJECT_ID');
        $MIX_FIREBASE_STORAGE_BUCKET = \env('MIX_FIREBASE_STORAGE_BUCKET');
        $MIX_FIREBASE_MESSAGE_SEENDER = \env('MIX_FIREBASE_MESSAGE_SEENDER');
        $MIX_FIREBASE_APP_ID = \env('MIX_FIREBASE_APP_ID');
        $MIX_FIREBASE_MEASUREMENT_ID = \env('MIX_FIREBASE_MEASUREMENT_ID');

        $script_content = <<<JAVASCRIPT
            importScripts("https://www.gstatic.com/firebasejs/8.2.7/firebase-app.js");
            importScripts("https://www.gstatic.com/firebasejs/8.2.7/firebase-messaging.js");

            var firebaseConfig = {
                apiKey: "$MIX_FIREBASE_API_KEY" ,
                authDomain: "$MIX_FIREBASE_AUTH_DOMAIN" ,
                projectId: "$MIX_FIREBASE_PROJECT_ID" ,
                storageBucket: "$MIX_FIREBASE_STORAGE_BUCKET" ,
                messagingSenderId: "$MIX_FIREBASE_MESSAGE_SEENDER" ,
                appId: "$MIX_FIREBASE_APP_ID" ,
                measurementId: "$MIX_FIREBASE_MEASUREMENT_ID" 
            };

            const app = firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();
        JAVASCRIPT;

        return $script_content;
    }

    public static function publishNow()
    {
        // File::put(public_path()."/data.text", "hello world")
    }
}
