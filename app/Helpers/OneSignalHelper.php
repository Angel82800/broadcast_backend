<?php
namespace App\Helpers;

class OneSignalHelper{
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic name
    public function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );
 
        return $this->sendPushNotification($fields);
    }

    // function makes curl request to firebase servers
    public static function sendMessage($tokens=array(),$development=true,$message=array(),$custom_data=array()){
        // if($message != null || count($message) != 0)
        //     for($i=0;$i<count($message);$i++){
                $content = array(
                    "en" => 'Testing Message'
                );
            
                $fields = array(
                    'app_id' => env('ONESIGNAL_APP_ID'),
                    'included_segments' => array('All'),
                    'data' => array("foo" => "bar"),
                    'large_icon' =>"ic_launcher_round.png",
                    'contents' => $content
                );
                $fields = json_encode($fields);
                print($fields);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                        'Authorization: Basic '.env('ONESIGNAL_APP_KEY')));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    

                $response = curl_exec($ch);
                curl_close($ch);
            //}
    }
}
?>