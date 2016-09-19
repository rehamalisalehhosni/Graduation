<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\FavoriteRealEstateAd;
use App\Models\Directory;
use App\Models\User;
use App\Models\RealEstateAd;
use Tymon\JWTAuth\Facades\JWTAuth;

class RealStateFavoriteController extends Controller
{
    public function __construct() {
        $this->middleware('guest');
        $this->middleware('jwt.auth');
    }

    public function postAdd(Request $request)
    {

         $user = JWTAuth::parseToken()->authenticate();

         $user_id = $user->user_id;

         $realstate_id = $request->realstate_id;
         if(trim($realstate_id) != "" and trim($user_id) != ""){
            if(is_numeric($realstate_id) and is_numeric($user_id)){
                 $check_fav = FavoriteRealEstateAd::where('user_id', $user_id  )
                            ->where('real_estate_ad_id' , $realstate_id)
                            ->get();

                if(count($check_fav) < 1){
                    $check_user = User::where('user_id', $user_id  )
                            ->get();
                    $check_real_estate = RealEstateAd::where('real_estate_ad_id', $realstate_id  )
                            ->get();
                    if(count($check_real_estate) > 0 and count($check_user) > 0 ){
                        $query=DB::table('favorite_real_estate_ad')->insert(
                            ['user_id' => $user_id , 'real_estate_ad_id' => $realstate_id]
                        );
                        return response()->json(['result' => ['success' => 'true' , 'message' => 'Insertion Complete' , 'errorcode' => '0']]);
                    }else{
                        return response()->json(['result' => ['success' => 'false' , 'message' => 'No User id  or real state' , 'errorcode' => '4']]);
                    }


                }else{
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'Sorry you have choose this real state before' , 'errorcode' => '3']]);
                }

            }else{
                return response()->json(['result' => ['success' => 'false' , 'message' => 'Please  User_id and realstate_id  must be numeric' , 'errorcode' => '2']]);
            }
         }else{
            return response()->json(['result' => ['success' => 'true' , 'message' => 'Please Enter User_id and realstate_id' , 'errorcode' => '1']]);
         }

    }


    public function postDelete(Request $request)
    {
         $user = JWTAuth::parseToken()->authenticate();

         $user_id = $user->user_id;
         $favorite_id = $request->favorite_id;
         if(trim($favorite_id) != "" and trim($user_id) != ""){
            if(is_numeric($favorite_id) and is_numeric($user_id)){
                 $check_fav = FavoriteRealEstateAd::where('user_id', $user_id  )
                            ->where('favorite_real_estate_ad_id' , $favorite_id)
                            ->get();

                if(count($check_fav) > 0){
                        $delete_fav=FavoriteRealEstateAd::where('user_id', $user_id  )
                            ->where('favorite_real_estate_ad_id' , $favorite_id)->delete();
                        return response()->json(['result' => ['success' => 'true' , 'message' => 'Delete Favorite Real Estate' , 'errorcode' => '0']]);

                }else{
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'Sorry This Favorite Not Exist' , 'errorcode' => '3']]);
                }

            }else{
                return response()->json(['result' => ['success' => 'false' , 'message' => 'Please   favorite_id  must be numeric' , 'errorcode' => '2']]);
            }
         }else{
            return response()->json(['result' => ['success' => 'false' , 'message' => 'Please Enter  favorite_id' , 'errorcode' => '1']]);
         }
    }


    public function postList(Request $request)
    {
        //
        $user = JWTAuth::parseToken()->authenticate();

         $user_id = $user->user_id;
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
        $realstate_original=Directory::where('key','=','realstate_original')->first();
        $realstate_thumb=Directory::where('key','=','realstate_thumb')->first();
        $destinationPath =  url().$realstate_original->path;
        $destinationPathThumb =url().$realstate_thumb->path;

        $realState= FavoriteRealEstateAd::skip($start)->take($count)->where('user_id','=',$user->user_id)->get();
          foreach($realState as $key=>$value){
            $realState[$key]->realEstateAd['cover_image']=$destinationPath.$realState[$key]->realEstateAd['cover_image'];
//            $fav->user;
          }

          if(count($realState)>0){
            $output["result"]["success"]=true;
            $output["result"]["message"]="Success";
            $output["result"]["errorcode"]=0;
            $output["result"]["user"]=$user_id;
            $output["response"]['realstate']=$realState;
        }else{
            $output["result"]["message"]="No  Favorite Realstate found";
            $output["result"]["errorcode"]=1;
        }

       return response()->json($output);
    }
}
