<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\FavoriteDiscussion;
use App\Models\User;
use App\Models\Discussion;
use App\Models\DiscussionComment;
use App\Models\DiscussionTopic;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Directory;

class DiscussionFavoriteController extends Controller
{
    public function __construct() {
        $this->middleware('guest');
        $this->middleware('jwt.auth');
    }

    public function postAdd(Request $request)
    {

         $user = JWTAuth::parseToken()->authenticate();

         $user_id = $user->user_id;
         $disc_id = $request->input('disc_id');
         if(trim($disc_id) != "" and trim($user_id) != ""){
            if(is_numeric($disc_id) and is_numeric($user_id)){
                 $check_fav = FavoriteDiscussion::where('user_id', $user_id  )
                            ->where('discussion_id' , $disc_id)
                            ->get();

                if(count($check_fav) < 1){
                    $check_user = User::where('user_id', $user_id  )
                            ->get();
                    $check_discussion = Discussion::where('discussion_id', $disc_id  )
                            ->get();
                            
                    if(count($check_discussion) > 0 and count($check_user) > 0 ){
                        $query=DB::table('favorite_discussion')->insert(
                            ['user_id' => $user_id , 'discussion_id' => $disc_id]
                        );
                        return response()->json(['result' => ['success' => 'true' , 'message' => 'Insertion Complete' , 'errorcode' => '0']]);
                    }else{
                        return response()->json(['result' => ['success' => 'false' , 'message' => 'No User id  or Discussion' , 'errorcode' => '4']]);
                    }


                }else{
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'Sorry you have choose this Discussion before' , 'errorcode' => '3']]);
                }

            }else{
                return response()->json(['result' => ['success' => 'false' , 'message' => 'Please  User_id and rDiscussion_id must be numeric' , 'errorcode' => '2']]);
            }
         }else{
            return response()->json(['result' => ['success' => 'true' , 'message' => 'Please Enter User_id and Discussion_id' , 'errorcode' => '1']]);
         }

    }


    public function postDelete(Request $request)
    {
         $user = JWTAuth::parseToken()->authenticate();

         $user_id = $user->user_id;
         $disc_id = $request->input('disc_id');
         if(trim($disc_id) != "" and trim($user_id) != ""){
            if(is_numeric($disc_id) and is_numeric($user_id)){
                 $check_disc = FavoriteDiscussion::where('user_id', $user_id  )
                            ->where('discussion_id' , $disc_id)
                            ->get();

                if(count($check_disc) > 0){
                        $delete_disc = FavoriteDiscussion::where('user_id', $user_id  )
                            ->where('discussion_id' , $disc_id)
                            ->delete();
                        return response()->json(['result' => ['success' => 'true' , 'message' => 'Delete Favorite Discussion' , 'errorcode' => '0']]);

                }else{
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'Sorry This Favorite Not Exist' , 'errorcode' => '3']]);
                }

            }else{
                return response()->json(['result' => ['success' => 'false' , 'message' => 'Please  User_id and discussion id  must be numeric' , 'errorcode' => '2']]);
            }
         }else{
            return response()->json(['result' => ['success' => 'false' , 'message' => 'Please Enter User id and discussion id' , 'errorcode' => '1']]);
         }
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

         $user_id = $user->user_id;
         $discussion_original=Directory::where('key','=','discussion_original')->first();
         $destinationPath =  url().$discussion_original->path;
         $user_original =Directory::where('key','=','users_original')->first();
         $userDestinationPath =  url().$user_original->path;

        if(!empty($user_id)){

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


                    $discussions = DB::select("select favorite_discussion.favorite_discussion_id , discussion.created_at ,  discussion.discussion_id , discussion.title , discussion.details , user.first_name , user.last_name , discussion.cover_image , discussion.user_id  , user.image as user_image 
                        from favorite_discussion ,  discussion  , user 
                        where favorite_discussion.user_id = ".$user_id."  and   discussion.user_id = user.user_id and favorite_discussion.discussion_id = discussion.discussion_id
                        order by favorite_discussion.favorite_discussion_id DESC
                         ");
                

                 foreach ($discussions as $discussion){
                        $image=DB::table('discussion_image')
                            ->select('discussion_image.image')
                            ->where('discussion_image.discussion_id',$discussion->discussion_id)->get();
                        $comments_number= DiscussionComment::where('discussion_id','=',$discussion->discussion_id)->count();

                        $images=array();
                        $images=$image;
                        foreach ($images as $key => $value) {
                            $images[$key]=$destinationPath.$value->image;
                        }

                        if ($discussion->user_id==$user->user_id){
                            $is_owner=1;
                        }else{
                            $is_owner=0;
                        }
                        
                        $discussion->user_image=$userDestinationPath.$discussion->user_image;
                        $discussion->cover_image=$destinationPath.$discussion->cover_image;
                        
                        $discussion->is_owner=$is_owner;
                        $discussion->disc_imgs=array();
                        $discussion->disc_imgs=$images;
                        $discussion->comments_no=$comments_number;
                    }


            
            if(count($discussions)>0){
                $output["result"]["success"]=true;
                $output["result"]["message"]="Success";
                $output["result"]["errorcode"]=0;
                $output["response"]=$discussions;

            }else{
                $output["result"]["message"]="No Favorite Discussions  found";
                $output["result"]["errorcode"]=1;
            }


        }else{
                $output["result"]["message"]="No User Id Found";
                $output["result"]["errorcode"]=2;
        }



       return response()->json($output);
    }
}
