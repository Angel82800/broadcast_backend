<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\Device;
use App\Model\Apps;
use App\Model\Video;
use App\Model\Video_event_log;

use App\Helpers\OneSignalHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\UrlGenerator;

class BroadcastApi extends Controller
{
    public function __construct() {
        header("Content-type:application/json");
    }

    public function userRegister(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $created_date = $request->input('created_date');
        $uuid = $request->input('uuid');
        $platform = $request->input('platform');
        $key = $request->input('key');
        $region = $request->input('region');
      
        $result = User::where("email",$email)->get();
        $send_result = array();
        
        if($result && count($result) > 0){
            $send_result = array("result"=>"fail","code"=>"registered");
        }else{
            $user = new User();
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->created_date = $created_date;
            $user->save();

            $saved_result = User::where('email',$email)->get();
            $id = $saved_result[0]->id;

            $device = new Device();
            $device->user_id = $id;
            $device->uuid = $uuid;
            $device->created_date = $created_date;
            $device->platform = $platform;
            $device->save();

            $apps = new Apps();
            $apps->user_id = $id;
            $apps->key = $key;
            $apps->region = $region;
            $apps->created_date = $created_date;
            $apps->save();
            $send_result = array("result"=>"success","code"=>["id"=>$id,"email"=>$email,"created_date"=>$created_date]);
        }
        echo json_encode($send_result);
    }

    public function uploads(Request $request,UrlGenerator $url){
        $user_id = $request->input('user_id');
        $created_date = $request->input('created_date');
        $key = $request->input('key');
        $video = $request->file('video');
        $video_name = "";
        
        if($video != null){
            $video_name = time().".mov";
            $video->move(realpath(base_path('public/uploads/')),$video_name);
        }
        
        $saved_user = User::where('id',$user_id)->get();
        $saved_apps = Apps::where([['user_id',$user_id],["key",$key]])->get();
        $video = new Video();
        $video->user_id = $user_id;
        $video->file_path = $video_name;
        $video->url = $url->to('/').'/uploads/';
        $video->created_date = $created_date;
        if( count($saved_apps) > 0 )
            $video->region = $saved_apps[0]->region;
        $video->key = $key;
        $video->save();
        
        $saved_video = Video::where([["user_id",$user_id],["created_date",$created_date]])->get();
        $send_result = array("result"=>"success","code"=>["id"=>$saved_video[0]->id,'user_id'=>intval($user_id),
            'file_path'=>$saved_video[0]->file_path,'url'=>$saved_video[0]->url, 'created_date'=>$created_date,
            'region'=>$saved_apps[0]->region,'bucket_count'=>$saved_video[0]->bucket_count,
            'answer_count'=>$saved_video[0]->answer_count,'duration'=>$saved_video[0]->duration,
            'is_published'=>$saved_video[0]->is_published ]);
        echo json_encode($send_result);
    }

    public function events(Request $request){
        $video_id = $request->input('video_id');
        $uuid = $request->input('devices_uuid');
        $apps_key = $request->input('apps_key');
        $created_date = $request->input('created_date');
        $is_viewed = $request->input('is_viewed');
        $duration = $request->input('duration');
        
        $video_event_log = new Video_event_log();
        $video_event_log->video_id = $video_id;
        $video_event_log->device_uuid = $uuid;
        $video_event_log->app_key = $apps_key;
        $video_event_log->created_date = $created_date;
        $video_event_log->is_viewed = $is_viewed;
        $video_event_log->duration = $duration;;
        $video_event_log->save();

        //Video::increment('bucket_count',1,['id',$video_id]);
        $video = Video();
        $video->increment('bucket_count',1,['id',$video_id]);
        $send_result = array("result"=>"success");
        echo json_encode($send_result);
    }

    public function getvideolist(Request $request,UrlGenerator $url){
        $user_id = $request->input('user_id');
        $key = $request->input('key');
        $result = Video::where([['user_id',$user_id],['key',$key]])->get();
        if(count($result) > 0){
            $send_result = array("result"=>"success","code"=>$result);
            echo json_encode($send_result);
        }else{
            $send_result = array("result"=>"failed");
            echo json_encode($send_result);
        }
        
    }

    public function push(){
        //video_id, video_url, app_name
        //$custom_data = array("video_id")
        $tokens = [];
        OneSignalHelper::sendMessage($tokens, true);
    }

}