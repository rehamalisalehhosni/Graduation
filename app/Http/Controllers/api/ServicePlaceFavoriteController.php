<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Models\FavoriteServicePlace;
use App\Models\Directory;
use App\Models\ServicePlace;
use App\Models\ServicePlaceImage;
use App\Models\User;
use Input;
use Validator;
use Redirect;
use Tymon\JWTAuth\Facades\JWTAuth;
class ServicePlaceFavoriteController extends Controller
{
      public function __construct() {
        $this->middleware('guest');
        $this->middleware('jwt.auth');
    }

    public function postList(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
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
                    $service_logos=Directory::where('key','=','serviceplace_logos')->first();
                    $service_cover=Directory::where('key','=','serviceplace_cover')->first();
            $user_image=Directory::where('key','=','users_original')->first();
                    $cover = url().$service_cover->path;
                    $logo =url().$service_logos->path;
                    $image = url().$user_image->path;
                    $serviceplace_original=Directory::where('key','=','serviceplace_original')->first();
                    $serviceplace_thumb=Directory::where('key','=','serviceplace_thumb')->first();
                    $destinationPath =url(). $serviceplace_original->path;
                    $destinationPathThumb =url().$serviceplace_thumb->path;
                    $favorites = FavoriteServicePlace::skip($start)->take($count)->where('user_id','=',$user->user_id)->get();
                      foreach($favorites as $fav){
                        $fav->servicePlace;
                        $fav->servicePlace->logo = $logo.$fav->servicePlace->logo;
                        $fav->servicePlace->cover_image = $cover.$fav->servicePlace->cover_image;
                        $fav->user;
                        $fav->user->image = $image.$fav->user->image;
                      }


                      if(count($favorites)>0){
                        $output["result"]["success"]=true;
                        $output["result"]["message"]="Success";
                        $output["result"]["errorcode"]=0;
                        $output["response"]['serviceplaces']=$favorites;
                    }else{
                        $output["result"]["message"]="No Favorite found";
                        $output["result"]["errorcode"]=1;
                    }
       return response()->json($output);
    }

    public function postAdd(Request $request)
    {
       $user = JWTAuth::parseToken()->authenticate();
        $v = Validator::make($request->all(), [
                  'service_places_id' => 'required',
              ]);
              if ($v->fails())
               {
                  $output["result"]["message"]="Missing Parameters";
                  $output["result"]["errorcode"]=2;
               }else{
                  $data =   FavoriteServicePlace::where('user_id','=', $user->user_id)->where('service_place_id','=', $request->service_places_id)->get();
                 if(count($data)>0){
                  $output["result"]["message"]="this service exists";
                  $output["result"]["errorcode"]=3;
                 }else{
                    $newfav = new FavoriteServicePlace ;
                    $newfav->user_id= $user->user_id;
                    $newfav->service_place_id= $request->service_places_id;
                    $newfav->save();
                    if($newfav){
                      $output["result"]["success"]=true;
                      $output["result"]["message"]="Success";
                      $output["result"]["errorcode"]=0;
                      $output["response"]="success";
                    }

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
              'favorite_service_place_id' => 'required',
          ]);
        if ($v->fails())
          {
            $output["result"]["message"]="Missing Parameters";
            $output["result"]["errorcode"]=1;
          }else{
            $fav = FavoriteServicePlace::find($request->favorite_service_place_id);
            if($fav===null){
                  $output["result"]["message"]="No favorite  found";
                  $output["result"]["errorcode"]=2;
            }elseif($fav->user_id!=$user->user_id){
                  $output["result"]["message"]="No auth found";
                  $output["result"]["errorcode"]=2;
            }else{
                  $fav->delete();
                  $output["result"]["success"]=true;
                  $output["result"]["message"]="Success";
                  $output["result"]["errorcode"]=0;
            }
          }
        return response()->json($output);
    }
}
