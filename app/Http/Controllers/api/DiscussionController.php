<?php

namespace App\Http\Controllers\api;
use App\Models\DiscussionComment;
use App\Models\DiscussionImage;
use App\Models\DiscussionTopic;
use App\Models\UserConfig;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use App\Models\FavoriteDiscussion;
use App\Models\Discussion;
use \Illuminate\Database\QueryException;
use App\Models\Directory;


use App\Http\Requests;
use App\Http\Controllers\Controller;

class DiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('jwt.auth');

    }


    public function postTopiclist(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;

        $topics = DiscussionTopic::all(["discussion_topic_id as topic_id", "title_en as topic_en", "title_ar as topic_ar"]);

        if (count($topics) > 0) {
            $output["result"]["success"] = true;
            $output["result"]["message"] = "Success";
            $output["result"]["errorcode"] = 0;
            $output["response"]["total"] = count($topics);
            $output["response"]["topics"] = $topics;
        } else {
            $output["result"]["message"] = "No topics found";
            $output["result"]["errorcode"] = 2;
        }

        return $output;
    }

    public function postAdd(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'details' => 'required',
            'topic_id' => 'required|exists:discussion_topic,discussion_topic_id',
            'neighborhood_id' => 'required|exists:neighbarhood,neighbarhood_id',
        ]);
        if ($validator->fails()) {
            $output["result"]["message"] = "Missing Inputs";
            $output["result"]["errorcode"] = 1;
        } else {
            $inputs = $request->all();
            $inputs["neighbarhood_id"] = $request->neighborhood_id;
            $inputs["topics_id"] = $request->topic_id;
            try {
                if ($request->hasFile('images')){
                    $files = $request->file('images');
                    // Making counting of uploaded images
                    $file_count = count($files);
                    // start count how many uploaded
                    $uploadcount = 0;
                    $out=array();
                    $out['images']=$files;
                    if ($file_count >0) {
                        $array = array();
                        $i=0;
                        $discussion_original=Directory::where('key','=','discussion_original')->first();
                        $discussion_thumb=Directory::where('key','=','discussion_thumb')->first();
                        $destinationPathThumb =public_path().$discussion_thumb->path;
                        $destinationPath =  public_path().$discussion_original->path;
                        $rules = array('file' => 'required');
                        $destinationPathurl =  url().$discussion_original->path;

                        foreach($files as $file) {
                          $validator = Validator::make(array('file'=> $file), $rules);
                          if($validator->passes()){
                            $extension = $file->getClientOriginalExtension(); // getting image extensionrealstate_thumb
                            $image_name=explode('.', $file->getClientOriginalName());
                            $fileName = $image_name[0].'_'.str_random(12).'.'. time().'.'.$extension;// renameing image
                            $file->move($destinationPath, $fileName); // uploading file to given path
                             $all=$destinationPathurl.$fileName;
                             $thum=$destinationPathThumb.$fileName;
                            // $img = Image::make($all, array(
                            // 'width' => 40,
                            // 'height' => 40,
                            // 'grayscale' => true
                            // ))->save($thum);

//                            Image::make($all)->resize(200, 200)->save($thum);
                          }
                             if($i==0){
                               $inputs['cover_image']=$fileName;
                               $lastInserted = $user->discussion()->create($inputs)->discussion_id;
                             }else{
                               $discussionImage=  new DiscussionImage();
                               $discussionImage->image=$fileName;
                               $discussionImage->discussion_id=$lastInserted;
                               $discussionImage->save();

                             }
                             $i=$i+1;
                        }
                    }
                }else{
                  $lastInserted = $user->discussion()->create($inputs)->discussion_id;
                }
                $output["result"]["success"] = true;
                $output["result"]["message"] = "discussion just added ";
                $output["result"]["errorcode"] = 0;


            } catch (QueryException $ex) {
                $output["result"]["success"] = false;
                $output["result"]["message"] = "Failed to add discussion";
                $output["result"]["errorcode"] = 2;
            }

        }
        return $output;
    }

    public function postEdit(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'details' => 'required',
            'topic_id' => 'required|exists:discussion_topic,discussion_topic_id',
            'discussion_id' => 'required|exists:discussion',
        ]);
        if ($validator->fails()) {
            $output["result"]["message"] = "Missing Inputs";
            $output["result"]["errorcode"] = 1;
        } else {
            $inputs = $request->all();
            $inputs["neighbarhood_id"] = $request->neighborhood_id;
            $inputs["topics_id"] = $request->topic_id;
            try {

                $discussion = Discussion::find($request->discussion_id);
                $discussion->update($request->all());

                if ($request->hasFile('images')){

                    DB::table('discussion_image')->where('discussion_id', $discussion->discussion_id)->delete();
                    $files = $request->file('images');
                    // Making counting of uploaded images
                    $file_count = count($files);
                    // start count how many uploaded
                    $uploadcount = 0;
                    $out=array();
                    $out['images']=$files;
                    if ($file_count >0) {
                        $array = array();
                        foreach($files as $file) {
                            $discussion_original=Directory::where('key','=','discussion_original')->first();
                            $discussion_thumb=Directory::where('key','=','discussion_thumb')->first();
                            $destinationPathThumb =public_path().$discussion_thumb->path;
                            $destinationPath =  public_path().$discussion_original->path;
                            $destinationPathurl =  url().$discussion_original->path;
                            $rules = array('file' => 'required');
                            $validator = Validator::make(array('file'=> $file), $rules);

                            if($validator->passes()){
                                $extension = $file->getClientOriginalExtension(); // getting image extensionrealstate_thumb
                                $image_name=explode('.', $file->getClientOriginalName());
                                $fileName = $image_name[0].'_'.str_random(12).'.'. time().'.'.$extension;// renameing image
                                $file->move($destinationPath, $fileName); // uploading file to given path
                                $all=$destinationPathurl.$fileName;
                                $thum=$destinationPathThumb.$fileName;

                            //   Image::make($all)->resize(200, 200)->save($thum);
//                            $thum=$destinationPathThumb.$fileName;
                                /*                        $img = Image::make($all, array(
                                                        'width' => 40,
                                                        'height' => 40,
                                                        'grayscale' => true
                                                    ))->save($thum);*/
                                $discussionImage=  new DiscussionImage();
                                $discussionImage->image=$fileName;
                                $discussionImage->discussion_id=$discussion->discussion_id;
                                $discussionImage->save();
                            }
                        }
                    }
                }

                $output["result"]["success"] = true;
                $output["result"]["message"] = "Success";
                $output["result"]["errorcode"] = 0;
                $output["response"] = "success";

            } catch (QueryException $ex) {
                $output["result"]["success"] = false;
                $output["result"]["message"] = "Failed to edit discussion";
                $output["result"]["errorcode"] = 2;
            }
        }
        return $output;
    }

    public function postImagedelete(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $v = Validator::make($request->all(), [
            'image_id' => 'required',
        ]);
        if ($v->fails()) {
            $output["result"]["message"] = "Missing Inputs";
            $output["result"]["errorcode"] = 2;
        } else {
            $dis_image = DiscussionImage::find($request->image_id);
            if ($dis_image !== null) {
                $discussion = Discussion::find($dis_image->discussion_id);
            }
            if ($dis_image === null) {
                $output["result"]["message"] = "No image found";
                $output["result"]["errorcode"] = 1;
            } elseif ($discussion->user_id == $user->user_id) {
                $output["result"]["message"] = "No auth found";
                $output["result"]["errorcode"] = 3;
            } else {
                $dis_image = $dis_image->delete();
                $output["result"]["success"] = true;
                $output["result"]["message"] = "Success";
                $output["result"]["errorcode"] = 0;
                $output["result"]["dis_image"] = $dis_image;
            }
        }
        return $output;
    }






    public function postDelete(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;

        $rules = [
            'discussion_id' => 'required|integer|exists:discussion'
        ];

        $input = Input::only(
            'discussion_id'
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()){
            $output["result"]["message"]=$validator->errors();
            if ($output["result"]["message"]->has('discussion_id')){
                $output["result"]["errorcode"]=10;
            } else{
                $output["result"]["errorcode"]=1;
            }

        }else{
            $discussion = Discussion::where('discussion_id',$request['discussion_id'])->where('user_id',$user->user_id)->first();
            if ($discussion){
                $discussion->delete();
                $output["result"]["success"]=true;
                $output["result"]["message"]="Your discussion successfully deleted ";
                $output["result"]["errorcode"]=0;
            }else{
                $output["result"]["message"]="It isn't your discussion or you don't have a discussion with that ID";
            }
        }

        return $output;
    }

    public function postList(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $discussions=0;

        $rules = [
            'user_id'=>"integer",
            "neighborhood_id"=>"integer|exists:neighbarhood,neighbarhood_id",
            "topic_id"=>"integer|exists:discussion_topic,discussion_topic_id",
            "keyword"=>"string",
            'start' => 'integer',
            'count' => 'integer'

        ];
        $discussion_original=Directory::where('key','=','discussion_original')->first();
       //$destinationPath =  $_SERVER['SERVER_NAME'].'public/'.$discussion_original->path;
        $destinationPath =  url().$discussion_original->path;
        $user_original =Directory::where('key','=','users_original')->first();
        $userDestinationPath =  url().$user_original->path;

         $input = Input::only(
            'start' ,
            'count',
            'neighborhood_id',
            'user_id',
            "topic_id",
            "keyword"

        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()){
            $output["result"]["errorcode"]=1;
            $output["result"]["message"]=" start or count is not integer ,it should be! ";
        }else{
            $fav_disc_ids = DB::table('favorite_discussion')->select('discussion_id')->where('user_id',$user->user_id)->get();
            if (!$request->has("start") && ! $request->has("count")){
                $request['start']=0;
                $request['count']=10;
            }
            if ( ! $request->has("neighborhood_id")&&!$request->has("user_id") &&! $request->has("topic_id") && ! $request->has("keyword"))
            {
                $userNeighborhood=UserConfig::where("user_id",$user->user_id)->where("is_logout",0)->first();
                $discussions= DB::table('discussion')
                    ->select("discussion.discussion_id" , "discussion.topics_id as topic_id","discussion.title" ,"discussion.cover_image", "discussion.details" ,"discussion.user_id","discussion.created_at","discussion.updated_at"
                        ,"discussion_topic.title_ar as discussion_topic_title_ar","discussion_topic.title_en as discussion_topic_title_en","user.first_name","user.image as user_image" )
                    ->join('discussion_topic', 'discussion_topic.discussion_topic_id', '=', 'discussion.topics_id')
                    ->join("user","user.user_id","=","discussion.user_id")
                    ->where('discussion.neighbarhood_id' , $userNeighborhood->neighbarhood_id)->orderBy('discussion.discussion_id', 'DESC')
                    ->skip($request['start'])->take($request['count']);
                $discussions=$discussions->get();

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
                        $is_fav=0;
                        foreach ($fav_disc_ids as $fav){
                            if ($fav->discussion_id==$discussion->discussion_id){
                                $is_fav=1;
                            }
                        }
                        $discussion->user_image=$userDestinationPath.$discussion->user_image;
                        $discussion->cover_image=$destinationPath.$discussion->cover_image;
                        $discussion->is_fav=$is_fav;
                        $discussion->is_owner=$is_owner;
                        $discussion->disc_imgs=array();
                        $discussion->disc_imgs=$images;
                        $discussion->comments_no=$comments_number;
                    }


                if(count($discussions)>0){
                    $output["result"]["success"]=true;
                    $output["result"]["message"]="Success";
                    $output["result"]["errorcode"]=0;
                    $output["response"]['discussionlist']=$discussions;

                }else{
                    $output["result"]["message"]="No Discussion found";
                    $output["result"]["errorcode"]=1;
                }

            }elseif  ($request->has("user_id")){
                $discussions= DB::table('discussion')
                    ->select("discussion.discussion_id" , "discussion.topics_id as topic_id","discussion.title" ,"discussion.cover_image", "discussion.details" ,"discussion.user_id","discussion.created_at","discussion.updated_at"
                        ,"discussion_topic.title_ar as discussion_topic_title_ar","discussion_topic.title_en as discussion_topic_title_en","user.first_name","user.image as user_image" )
                    ->join('discussion_topic', 'discussion_topic.discussion_topic_id', '=', 'discussion.topics_id')
                    ->join("user","user.user_id","=","discussion.user_id")
                    ->where('discussion.user_id' , $user->user_id)->orderBy('discussion.discussion_id', 'DESC')
                    ->skip($request['start'])->take($request['count']);

                $discussions=$discussions->get();
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
                        $is_fav=0;
                        foreach ($fav_disc_ids as $fav){
                            if ($fav->discussion_id==$discussion->discussion_id){
                                $is_fav=1;
                            }
                        }
                        $discussion->user_image=$userDestinationPath.$discussion->user_image;
                        $discussion->cover_image=$destinationPath.$discussion->cover_image;
                        $discussion->is_fav=$is_fav;
                        $discussion->is_owner=$is_owner;
                        $discussion->disc_imgs=array();
                        $discussion->disc_imgs=$images;
                        $discussion->comments_no=$comments_number;
                    }



                if(count($discussions)>0){
                    $output["result"]["success"]=true;
                    $output["result"]["message"]="Success";
                    $output["result"]["errorcode"]=0;
                    $output["response"]['mydiscussionlist']=$discussions;

                }else{
                    $output["result"]["message"]="No Discussion found";
                    $output["result"]["errorcode"]=1;
                }

            }elseif ( $request->has("topic_id") ){
                $discussions= DB::table('discussion')
                    ->select("discussion.discussion_id" , "discussion.topics_id as topic_id","discussion.title" ,"discussion.cover_image", "discussion.details" ,"discussion.user_id","discussion.created_at","discussion.updated_at"
                        ,"discussion_topic.title_ar as discussion_topic_title_ar","discussion_topic.title_en as discussion_topic_title_en","user.first_name","user.image as user_image" )
                    ->join('discussion_topic', 'discussion_topic.discussion_topic_id', '=', 'discussion.topics_id')
                    ->join("user","user.user_id","=","discussion.user_id")
                    ->where('discussion_topic.discussion_topic_id' ,$request['topic_id'])->orderBy('discussion.discussion_id', 'DESC')
                    ->skip($request['start'])->take($request['count']);


                $discussions=$discussions->get();
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
                        $is_fav=0;
                        foreach ($fav_disc_ids as $fav){
                            if ($fav->discussion_id==$discussion->discussion_id){
                                $is_fav=1;
                            }
                        }
                        $discussion->user_image=$userDestinationPath.$discussion->user_image;
                        $discussion->cover_image=$destinationPath.$discussion->cover_image;
                        $discussion->is_fav=$is_fav;
                        $discussion->is_owner=$is_owner;
                        $discussion->disc_imgs=array();
                        $discussion->disc_imgs=$images;
                        $discussion->comments_no=$comments_number;
                    }



                if(count($discussions)>0){
                    $output["result"]["success"]=true;
                    $output["result"]["message"]="Success";
                    $output["result"]["errorcode"]=0;
                    $output["response"]['discussionlist']=$discussions;

                }else{
                    $output["result"]["message"]="No Discussion found";
                    $output["result"]["errorcode"]=1;
                }

            }elseif ( $request->has("keyword") ){
                $discussions= DB::table('discussion')
                    ->select("discussion.discussion_id" , "discussion.topics_id as topic_id","discussion.title" ,"discussion.cover_image", "discussion.details" ,"discussion.user_id","discussion.created_at","discussion.updated_at"
                        ,"discussion_topic.title_ar as discussion_topic_title_ar","discussion_topic.title_en as discussion_topic_title_en","user.first_name","user.image as user_image" )
                    ->join('discussion_topic', 'discussion_topic.discussion_topic_id', '=', 'discussion.topics_id')
                    ->join("user","user.user_id","=","discussion.user_id")
                    ->where('discussion.details' ,'like','%'.$request['keyword'].'%')->orderBy('discussion.discussion_id', 'DESC')
                    ->skip($request['start'])->take($request['count']);


                $discussions=$discussions->get();
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
                        $is_fav=0;
                        foreach ($fav_disc_ids as $fav){
                            if ($fav->discussion_id==$discussion->discussion_id){
                                $is_fav=1;
                            }
                        }
                        $discussion->user_image=$userDestinationPath.$discussion->user_image;
                        $discussion->cover_image=$destinationPath.$discussion->cover_image;
                        $discussion->is_fav=$is_fav;
                        $discussion->is_owner=$is_owner;
                        $discussion->disc_imgs=array();
                        $discussion->disc_imgs=$images;
                        $discussion->comments_no=$comments_number;
                    }



                if(count($discussions)>0){
                    $output["result"]["success"]=true;
                    $output["result"]["message"]="Success";
                    $output["result"]["errorcode"]=0;
                    $output["response"]['discussionlist']=$discussions;

                }else{
                    $output["result"]["message"]="No Discussion found";
                    $output["result"]["errorcode"]=1;
                }

            }
        }



        return  $output;
    }
    
    public function postShow(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;

        $validator = Validator::make($request->all(), [
                    'discussion_id' => 'required',
        ]);

        if ($validator->fails()) {
            $output["result"]["message"] = "Missing Parameters";
            $output["result"]["errorcode"] = 2;
        } else {

            $is_owner = 0;
            $is_fav = 0;

            $discussion = Discussion::find($request->discussion_id);
            if ($user->user_id == $discussion->user_id) {
                $is_owner = 1;
            }

            $fav = FavoriteDiscussion::where('user_id', '=', $user->user_id)->where('discussion_id', '=', $discussion->discussion_id)->get();
            if (count($fav) > 0) {
                $is_fav = 1;
            }

            $discussion['is_owner'] = $is_owner;
            $discussion['is_fav'] = $is_fav;

            $images = $discussion->images;
            if (!empty($images)) {
                $discussion_original = Directory::where('key', '=', 'discussion_original')->first();
                $destinationPath = url() . $discussion_original->path;
                foreach ($images as $key => $value) {
                    $images[$key]['image'] = $destinationPath . $value->image;
                }
                $output["response"]["images"] = $images;
            }
            if ($discussion) {
                unset($discussion["cover_image"]);
                unset($discussion["updated_at"]);
                unset($discussion["on_home"]);
                unset($discussion["is_hide"]);
                $output["result"]["success"] = true;
                $output["result"]["message"] = "Success";
                $output["result"]["errorcode"] = 0;
                $output["response"] = $discussion;
            } else {
                $output["result"]["message"] = "No Discussion found";
                $output["result"]["errorcode"] = 1;
            }
        }
        return $output;
    }



}
