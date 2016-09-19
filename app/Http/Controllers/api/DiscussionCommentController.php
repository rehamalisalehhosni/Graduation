<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Discussion;
use App\Models\User;
use App\Models\DiscussionComment;
use Input;
use Validator;
use Redirect;
use Image;
use App\Models\RealEstateAd;
use Tymon\JWTAuth\Facades\JWTAuth;


class DiscussionCommentController extends Controller
{
    //
   public function __construct() {
        $this->middleware('guest');
        $this->middleware('jwt.auth');
    }

    public function postEdit(Request $request)
    {
              $output=array();
              $output["result"]=array();
              $output["result"]["success"]=false;
              $output["result"]["message"]="Unknown error";
              $output["result"]["errorcode"]=10;
              $user = JWTAuth::parseToken()->authenticate();

              $user_id = $user->user_id;
              $v = Validator::make($request->all(), [
                  'comment' => 'required',
                  //'user_id' => 'required',
                  'disc_id' => 'required',
                  'discussion_comment_id' => 'required',
              ]);
              if ($v->fails())
               {
                  $output["result"]["message"]="Missing Parameters";
                  $output["result"]["errorcode"]=1;
               }else{

                  $checkDiscussionComment = DiscussionComment::where("user_id" , $user_id)
                                        ->where("discussion_comment_id" ,$request ->discussion_comment_id)
                                        ->get();

                  if(count($checkDiscussionComment) > 0){
                        $discussionComment = DiscussionComment::find($request ->discussion_comment_id);
                        if($discussionComment === NULL){
                            $output["result"]["message"]="Sorry This Comment Not Found";
                            $output["result"]["errorcode"]=2;
                        }else{

                              $discussionComment->comment = $request->comment;

                              $check_user = User::where('user_id', $user_id  )
                                            ->get();
                              $check_discussion = Discussion::where('discussion_id', $request->disc_id  )
                                      ->get();
                              if(count($check_discussion) > 0 and count($check_user) > 0 ){
                                  $discussionComment->save();
                                  $output["result"]["success"]=true;
                                  $output["result"]["message"]="Success";
                                  //$output["result"]["uploadcount"]=$uploadcount;
                                  $output["result"]["errorcode"]=0;
                                  $output["response"]="success";
                              }else{
                                $output["result"]["message"]="Sorry invalid user id  discussion id";
                                $output["result"]["errorcode"]=3;
                              }
                        }
                    }else{
                      $output["result"]["message"]="Sorry No Comment Found";
                      $output["result"]["errorcode"]=4;
                    }
               }
        return response()->json($output);
    }


    public function postAdd(Request $request)
    {
              $output=array();
              $output["result"]=array();
              $output["result"]["success"]=false;
              $output["result"]["message"]="Unknown error";
              $output["result"]["errorcode"]=10;
              $user = JWTAuth::parseToken()->authenticate();

              $user_id = $user->user_id;

              $v = Validator::make($request->all(), [
                  'comment' => 'required',
                  //'user_id' => 'required',
                  'disc_id' => 'required',
              ]);
              if ($v->fails())
               {
                  $output["result"]["message"]="Missing Parameters";
                  $output["result"]["errorcode"]=2;
               }else{
              $discussionComment = new DiscussionComment;
              $discussionComment->comment = $request->comment;
              $discussionComment->discussion_id = $request->disc_id;
              $discussionComment->user_id = $user_id;
              $discussionComment->is_hide = 0;

              $check_user = User::where('user_id', $user_id )
                            ->get();
              $check_discussion = Discussion::where('discussion_id', $request->disc_id  )
                      ->get();

              if(count($check_discussion) > 0 and count($check_user) > 0 ){
                  $discussionComment->save();
                  $insertedId = $discussionComment->discussion_comment_id;

                  //Notifications
                  $notification = new PushNotificationController;
                  $owner_id = $discussionComment->discussion->user_id;
                  $sender_id = $user_id;
                  $type = "DiscussionComment";
                  $type_id = $request->disc_id;
                  if ($owner_id != $sender_id) {
                      $notify_res = $notification->getHandleNotification($owner_id, $sender_id, $type, $type_id);
                      if ($notify_res) {
                        $output["result"]["notification"] = "success";
                      }
                      $output["result"]["notification"] = "failed";
                  }
                  

                  $output["result"]["success"]=true;
                  $output["result"]["message"]="Success";
                  //$output["result"]["uploadcount"]=$uploadcount;
                  $output["result"]["errorcode"]=0;
                  $output["inserted_comment_id"]=$insertedId;
              }else{
            $output["result"]["message"]="Sorry invalid user id  or  discussion id";
            $output["result"]["errorcode"]=1;
          }
        }
        return response()->json($output);
    }


    public function postDelete(Request $Request)
    {
        //
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $user = JWTAuth::parseToken()->authenticate();

        $user_id = $user->user_id;
        $v = Validator::make($Request->all(), [
                  'discussion_comment_id' => 'required',
                  //'user_id' => 'required',
              ]);
              if ($v->fails())
               {
                  $output["result"]["message"]="Missing Parameters";
                  $output["result"]["errorcode"]=2;
               }else{

                    $discussionComment = DiscussionComment::where("discussion_comment_id" ,$Request ->discussion_comment_id )
                                        ->where("user_id" , $user_id)
                                        ->get();
                    if(count($discussionComment) > 0){
                        $real = DiscussionComment::find($Request->discussion_comment_id)->delete();
                          if(!$real){
                                $output["result"]["message"]="No Error in Delete";
                                $output["result"]["errorcode"]=2;

                          }else{
                                $output["result"]["success"]=true;
                                $output["result"]["message"]="Success";
                                $output["result"]["errorcode"]=0;
                          }

                    }else{
                        $output["result"]["message"]="No Comment Found";
                        $output["result"]["errorcode"]=1;
                    }
                }

        return response()->json($output);
    }


    public function postList(Request $request)
    {
       //
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $user = JWTAuth::parseToken()->authenticate();

        if($request->disc_id){

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



            $discussion = DiscussionComment::skip($start)->take($count)
            ->where('discussion_id',$request->disc_id)
            ->where('is_hide' , 0)
            ->orderBy('discussion_comment_id', 'DESC')
            ->get();

            foreach ($discussion as $key=>$value) {
              if ($value->user_id==$user->user_id){
                  $is_owner=1;
              }else{
                  $is_owner=0;
              }
              $discussion[$key]['is_owner']=$is_owner;
                foreach ($value->user as $ghg) {

                }
            }

            if(count($discussion)>0){
                $output["result"]["success"]=true;
                $output["result"]["message"]="Success";
                $output["result"]["errorcode"]=0;
                $output["response"]=$discussion;

            }else{
                $output["result"]["message"]="No Comments found";
                $output["result"]["errorcode"]=1;
            }


        }else{
                $output["result"]["message"]="please Select Discussion";
                $output["result"]["errorcode"]=2;
        }



       return response()->json($output);
    }


}
