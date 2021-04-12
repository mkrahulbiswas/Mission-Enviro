<?php

namespace app\Traits;

use Illuminate\Support\Facades\Auth;

use Exception;
use Carbon\Carbon;
use DB;

trait NotificationTrait
{
    function sendNotificationOnSaveCompleteTask($deviceToken, $notifyDetails, $deviceType)
    {
        if ($notifyDetails['deviceType'] == config('constants.android')) {
            $fields = array(
                'registration_ids' => [$notifyDetails['deviceToken']],
                'priority' => 'high',
                'data' => [
                    'title' => $notifyDetails['title'],
                    'message' => $notifyDetails['msg'],
                    'userId' => $notifyDetails['userId'],
                    'date' => $notifyDetails['date'],
                    'type' => $notifyDetails['type'],
                    'sound' => 'default',
                    "content_available" => true
                ]
            );
        } else {
            $fields = array(
                'registration_ids' => [$deviceToken],
                'priority' => 'high',
                'notification' => [
                    'title' => $notifyDetails['title'],
                    'message' => $notifyDetails['msg'],
                    'body' => $notifyDetails['msg'],
                    'userId' => $notifyDetails['userId'],
                    'date' => $notifyDetails['date'],
                    'type' => $notifyDetails['type'],
                    'sound' => 'default',
                    "content_available" => true
                ]
            );
        }

        $headers = array(
            config('constants.fcmKey'),
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        curl_close($ch);
        //return $result;
        if ($result === false) {
            return false;
        } else {
            return true;
        }
    }
}
