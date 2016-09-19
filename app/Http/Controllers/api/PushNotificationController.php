<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use PushNotification;
use \App\Models\User;
use \App\Models\Notification;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller {

    public function __construct() {
        $this->middleware('guest');
        $this->middleware('jwt.auth', ['except' => ['getHandleNotification', 'getSend']]);
    }

    function getHandleNotification($owner_id, $sender_id, $type, $type_id) {
        $deviceToken = array();
        $user = User::find($owner_id);

        foreach ($user->userconfigs as $userconfig) {
            if ($userconfig->notification != 0 && $userconfig->is_logout != 1) {
                $deviceToken[] = $userconfig->device_token;
            }
        }

        $message = ["sender_id" => $sender_id, "type" => $type, "type_id" => $type_id, "owner_id" => $owner_id];
        if (!empty($deviceToken)) {
            return $this->getSend($deviceToken, $message);
        }
    }

    public function getSend($deviceToken, $message) {
        $devices = array();

        foreach ($deviceToken as $token) {
            $devices[] = PushNotification::Device($token);
        }
        $owner_id = array_pop($message);


        $deviceCollection = PushNotification::DeviceCollection($devices);

        $collection = PushNotification::app('Jeeran')
                ->to($deviceCollection)
                ->send(json_encode($message));

        foreach ($collection->pushManager as $push) {
            $response = $push->getAdapter()->getResponse();
        }

        foreach ($response->getResponse()["results"][0] as $key => $value) {
            if ($key == "error") {
                return 0;
            } else {
                $message["owner_id"] = $owner_id;
                Notification::create($message);
                return 1;
            }
        }
    }

    function getList(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $user = JWTAuth::parseToken()->authenticate();


        if ($request->count) {
            $count = $request->count;
        } else {
            $count = 10;
        }
        if ($request->start) {
            $start = $request->start;
        } else {
            $start = 0;
        }

        $notifications = Notification::skip($start)->take($count)
                ->orderBy('notifications_id', 'DESC')
                ->get();

        if (count($notifications) > 0) {
            $output["result"]["success"] = true;
            $output["result"]["message"] = "Success";
            $output["result"]["errorcode"] = 0;
            $output["response"] = $notifications;
        } else {
            $output["result"]["message"] = "No Notifications found";
            $output["result"]["errorcode"] = 1;
        }

        return $output;
    }

    function postUpdateseen(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $user = JWTAuth::parseToken()->authenticate();

        $rules = [
            'notifications_id' => 'required|integer|exists:notifications,notifications_id',
        ];
        $input = Input::only('notifications_id');
        $validator = Validator::make($input, $rules);

        if (!$validator->fails()) {

            $notifications = Notification::find($request->notifications_id);

            $notifications->read_at = date('Y-m-d H:i:s');
            $notifications->seen = 1;


            try {
                $notifications->update();
                $output["result"]["success"] = true;
                $output["result"]["message"] = "Success";
                $output["result"]["errorcode"] = 0;
                $output["response"] = "success";
            } catch (Exception $ex) {
                $output["result"]["success"] = false;
                $output["result"]["message"] = "Failed to update seen";
                $output["result"]["errorcode"] = 2;
            }
        } else {
            $output["result"]["success"] = false;
            $output["result"]["message"] = "Missing Inputs";
            $output["result"]["errorcode"] = 1;
        }

        return $output;
    }

}

