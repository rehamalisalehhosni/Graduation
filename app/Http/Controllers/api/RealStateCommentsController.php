<?php

namespace App\Http\Controllers\api;
use App\Models\RealEstateAdComment;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Input;
use Validator;
use Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;

class RealStateCommentsController extends Controller
{
    //
      public function __construct() {
        $this->middleware('guest');
        $this->middleware('jwt.auth');
    }


    public function postEdit(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $v = Validator::make($request->all(), [
                  'comment_id' => 'required',
                  'comment' => 'required',
                  'realstate_id' => 'required',
              ]);
              if ($v->fails())
               {
                  $output["result"]["message"]="Missing Parameters";
                  $output["result"]["errorcode"]=2;
               }else{
                  $realcomment =RealEstateAdComment::find($request->comment_id);
                  if($realcomment===null){
                    $output["result"]["message"]="No comment found";
                    $output["result"]["errorcode"]=1;

                  }elseif($user->user_id!=$realcomment->user_id){
                    $output["result"]["message"]="No auth";
                    $output["result"]["errorcode"]=3;
                  }else{
                        $realcomment->real_estate_ad_id = $request->realstate_id;
                        $realcomment->comment = $request->comment;
                        $realcomment->user_id = $user->user_id;
                        $realcomment->update();
                        $output["result"]["success"]=true;
                        $output["result"]["message"]="Success";
                        $output["result"]["errorcode"]=0;
                        $output["response"]="success";
                  }
               }
       return response()->json($output);
    }


    public function postAdd(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $v = Validator::make($request->all(), [
                  'comment' => 'required',
                  'realstate_id' => 'required',
              ]);
        if ($v->fails())
          {
             $output["result"]["message"]="Missing Parameters";
             $output["result"]["errorcode"]=2;
          }else{

            $realcomment = new RealEstateAdComment;
            $realcomment->real_estate_ad_id = $request->realstate_id;
            $realcomment->comment = $request->comment;
            $realcomment->user_id = $user->user_id;
            $realcomment->save();

            if($realcomment){

                  //Notifications
                  $notification = new PushNotificationController;
                  $owner_id = $realcomment->realEstateAd->user_id;
                  $sender_id = $user->user_id;
                  $type = "RealestateComment";
                  $type_id = $request->realstate_id;
                  if ($owner_id != $sender_id) {
                      $notify_res = $notification->getHandleNotification($owner_id, $sender_id, $type, $type_id);
                      if ($notify_res) {
                        $output["result"]["notification"] = "success";
                      }
                      $output["result"]["notification"] = "failed";
                  }

                  $output["result"]["success"]=true;
                  $output["result"]["message"]="Success";
                  $output["response"]['real_estate_ad_comment_id']=$realcomment->real_estate_ad_comment_id;
                  $output["result"]["errorcode"]=0;
            }
          }
       return response()->json($output);
    }

    public function postDelete(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $v = Validator::make($request->all(), [
                  'comment_id' => 'required',
                  'realstate_id' => 'required',
              ]);
        if ($v->fails())
          {
             $output["result"]["message"]="Missing Parameters";
             $output["result"]["errorcode"]=2;
          }else{
              $real = RealEstateAdComment::find($request->comment_id);
              if($real===null){
                    $output["result"]["message"]="no comment found";
                    $output["result"]["errorcode"]=1;

              }elseif($user->user_id!=$real->user_id){
                    $output["result"]["message"]="no auth";
                    $output["result"]["errorcode"]=3;
              }else{
                    $real->delete();
                    $output["result"]["success"]=true;
                    $output["result"]["message"]="Success";
                    $output["response"]['realstate_id']=$request->realstate_id;
                    $output["result"]["errorcode"]=0;
              }
          }
       return response()->json($output);
     }


    public function postList(Request $request)
    {
        //
        $user = JWTAuth::parseToken()->authenticate();
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $v = Validator::make($request->all(), [
                  'realstate_id' => 'required',
              ]);
        if ($v->fails())
          {
             $output["result"]["message"]="Missing Parameters";
             $output["result"]["errorcode"]=2;
          }else{

            if($request->count){
                $count= $request->count;
              }else{
                  $count=20;
              }
                  if($request->start){
                    $start= $request->start;
                  }else{
                      $start=0;
                  }
                  $comments = RealEstateAdComment::where('real_estate_ad_id','=',$request->realstate_id)->orderBy('real_estate_ad_comment_id', 'DESC')->skip($start)->take($count)->get();
                   foreach ($comments as $key => $value) {
                     # code...
                     $comments[$key]['is_owner']=0;
                     if($value->user_id==$user->user_id){
                       $comments[$key]['is_owner']=1;

                     }
                   }
                  if(count($comments)>0){
                      $output["result"]["success"]=true;
                      $output["result"]["message"]="Success";
                      $output["result"]["errorcode"]=0;
                      $output["response"]=$comments;
                  }else{
                      $output["result"]["message"]="No Realstate comment found";
                      $output["result"]["errorcode"]=1;
                  }
             }
       return response()->json($output);
    }
}
