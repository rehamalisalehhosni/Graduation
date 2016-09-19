<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Input;
use \Illuminate\Database\QueryException;
use \App\Models\ServicePlaceReview;
use App\Models\Directory;

class ServiceReviewController extends Controller {

    public function __construct() {
        $this->middleware('guest');
        $this->middleware('jwt.auth');
    }

    public function postAdd(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $user = JWTAuth::parseToken()->authenticate();
        $rules = [
            'service_place_id' => 'required|integer|exists:service_place,service_place_id',
        ];
        $input = Input::only('service_place_id');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $output["result"]["message"] = "Service not found";
            $output["result"]["errorcode"] = 2;
            return $output;
        }
        $validator = Validator::make(["rating" => $request->rating], ["rating" => "in:1,2,3,4,5"]);
        if ($validator->fails()) {
            $output["result"]["message"] = "Rating must be in range(1,5)";
            $output["result"]["errorcode"] = 3;
            return $output;
        }
        if ($user->user_id && $request->has("service_place_id") && (($request->has("rating") && in_array($request->get("rating"), range(1, 5))) || $request->has("review"))) {
            try {
                $user->servicePlaceReviews()->create($request->all());

                //Notifications
                $notification = new PushNotificationController;
                $service_place = ServicePlace::find($request->service_place_id);
                $owner_id = $service_place->user_id;
                $sender_id = $user->user_id;

                $type_id = $request->service_place_id;
                if ($owner_id != $sender_id) {
                    if ($request->has("rating")) {
                        $type = "ServiceRate";
                        $notify_res = $notification->getHandleNotification($owner_id, $sender_id, $type, $type_id);
                      if ($notify_res) {
                        $output["result"]["notification"] = "success";
                      }
                      $output["result"]["notification"] = "failed";
                    }
                    if ($request->has("review")) {
                        $type = "ServiceReview";
                        $notify_res = $notification->getHandleNotification($owner_id, $sender_id, $type, $type_id);
                      if ($notify_res) {
                        $output["result"]["notification"] = "success";
                      }
                      $output["result"]["notification"] = "failed";
                    }
                }
		

                $output["result"]["success"] = true;
                $output["result"]["message"] = "Success";
                $output["result"]["errorcode"] = 0;
                $output["response"] = "success";
            } catch (QueryException $ex) {
                $output["result"]["success"] = false;
                $output["result"]["message"] = "Failed to add review";
                $output["result"]["errorcode"] = 4;
            }
        } else {
            $output["result"]["success"] = false;
            $output["result"]["message"] = "Missing Inputs";
            $output["result"]["errorcode"] = 1;
        }

        return $output;
    }

    public function postEdit(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $user = JWTAuth::parseToken()->authenticate();
        $rules = [
            'service_place_review_id' => 'required|integer|exists:service_place_review,service_place_review_id',
        ];
        $input = Input::only('service_place_review_id');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $output["result"]["message"] = "Service review not found";
            $output["result"]["errorcode"] = 2;
            return $output;
        }
        $validator = Validator::make(["rating" => $request->rating], ["rating" => "in:1,2,3,4,5"]);
        if ($validator->fails()) {
            $output["result"]["message"] = "Rating must be in range(1,5)";
            $output["result"]["errorcode"] = 3;
            return $output;
        }
        if ($user->user_id && $request->has("service_place_review_id") && (($request->has("rating") && in_array($request->get("rating"), range(1, 5))) || $request->has("review"))) {
            $service_review = ServicePlaceReview::find($request->service_place_review_id);
            if ($request->has("rating") && in_array($request->get("rating"), range(1, 5))) {
                $service_review->rating = $request->get('rating');
            }
            if ($request->has("review")) {
                $service_review->review = $request->get('review');
            }
            try {
                $service_review->update();
                $output["result"]["success"] = true;
                $output["result"]["message"] = "Success";
                $output["result"]["errorcode"] = 0;
                $output["response"] = "success";
            } catch (Exception $ex) {
                $output["result"]["success"] = false;
                $output["result"]["message"] = "Failed to update review";
                $output["result"]["errorcode"] = 4;
            }
        } else {
            $output["result"]["success"] = false;
            $output["result"]["message"] = "Missing Inputs";
            $output["result"]["errorcode"] = 1;
        }

        return $output;
    }

    public function postDelete(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $user = JWTAuth::parseToken()->authenticate();
        $rules = [
            'service_place_review_id' => 'required|integer|exists:service_place_review,service_place_review_id',
        ];
        $input = Input::only('service_place_review_id');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $output["result"]["message"] = "Service review not found";
            $output["result"]["errorcode"] = 2;
            return $output;
        }

        if ($user->user_id && $request->has("service_place_review_id")) {
            try {
                ServicePlaceReview::find($request->service_place_review_id)->delete();
                $output["result"]["success"] = true;
                $output["result"]["message"] = "Success";
                $output["result"]["errorcode"] = 0;
                $output["response"] = "success";
            } catch (Exception $ex) {
                $output["result"]["success"] = false;
                $output["result"]["message"] = "Failed to delete review";
                $output["result"]["errorcode"] = 3;
            }
        } else {
            $output["result"]["success"] = false;
            $output["result"]["message"] = "Missing Inputs";
            $output["result"]["errorcode"] = 1;
        }

        return $output;
    }

    function getList(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $user = JWTAuth::parseToken()->authenticate();
        $user_image=Directory::where('key','=','users_original')->first();
        $image = url().$user_image->path;
        if ($request->service_place_id) {

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

            $service_review = ServicePlaceReview::skip($start)->take($count)
                    ->where('service_place_id', $request->service_place_id)
                    ->where('is_hide', 0)
                    ->orderBy('service_place_id', 'DESC')
                    ->get();

            foreach ($service_review as $key => $value) {
                if ($value->user_id == $user->user_id) {
                    $is_owner = 1;
                } else {
                    $is_owner = 0;
                }
                $service_review[$key]['is_owner'] = $is_owner;
                $value->user;
                $value->user->image = $image.$value->user->image;
                unset($value->user->verify_email);
                unset($value->user->last_forget_password);
                unset($value->user->is_active);
                unset($service_review[$key]['is_hide']);
                unset($service_review[$key]['user_id']);

            }
            //$image = url().$user_image->path;
            //return $value->user->image;
            if (count($service_review) > 0) {
                $output["result"]["success"] = true;
                $output["result"]["message"] = "Success";
                $output["result"]["errorcode"] = 0;
                $output["response"] = $service_review;
            } else {
                $output["result"]["message"] = "No Reviews found";
                $output["result"]["errorcode"] = 1;
            }
        } else {
            $output["result"]["message"] = "Missing service_place_id";
            $output["result"]["errorcode"] = 2;
        }

        return $output;
    }

}
