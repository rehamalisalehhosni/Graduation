<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\RealEstateAd;
use App\Models\RealEstateAdImage;
use App\Models\FavoriteRealEstateAd;
use App\Models\Amenity;
use App\Models\UnitType;
use App\Models\Directory;
use Input;
use Validator;
use Redirect;
use Image;
use Tymon\JWTAuth\Facades\JWTAuth;
class RealstateController extends Controller
{
    //
    public function __construct() {
        $this->middleware('guest');
        $this->middleware('jwt.auth');
    }
    public function postAmenitelist(){
          $output=array();
          $output["result"]=array();
          $output["result"]["success"]=false;
          $output["result"]["message"]="Unknown error";
          $output["result"]["errorcode"]=10;
          $amenity=Amenity::all();
          if(count($amenity)>0){
            $output["result"]["success"]=true;
            $output["result"]["message"]="Success";
            $output["result"]["errorcode"]=0;
            $output["response"]=$amenity;
        }else{
            $output["result"]["message"]="No Amenity found";
            $output["result"]["errorcode"]=1;
        }
       return response()->json($output);
    }
    public function  postImagefeature(){
      $output=array();
      $output["result"]=array();
      $output["result"]["success"]=false;
      $output["result"]["message"]="Unknown error";
      $output["result"]["errorcode"]=10;
      $image=RealEstateAd::where('is_featured','=','1')->take(5)->get();
      $realstate_original=Directory::where('key','=','realstate_original')->first();
      $realstate_thumb=Directory::where('key','=','realstate_thumb')->first();
      $destinationPath = url().$realstate_original->path;
      $destinationPathThumb =url().$realstate_thumb->path;
      $data=array();
      foreach ($image as $key=>$value) {
        $data[$key]=array();
        $data[$key]['cover_image']=$destinationPath.$value->cover_image;
        $data[$key]['title']=$value->title;
      }
      if(count($image)>0){
        $output["result"]["success"]=true;
        $output["result"]["message"]="Success";
        $output["result"]["errorcode"]=0;
        $output["response"]=$data;
    }else{
        $output["result"]["message"]="No image found";
        $output["result"]["errorcode"]=1;
    }
    return response()->json($output);
    }
    public function postUnittypelist(){
          $output=array();
          $output["result"]=array();
          $output["result"]["success"]=false;
          $output["result"]["message"]="Unknown error";
          $output["result"]["errorcode"]=10;
          $unitType=UnitType::all();
          if(count($unitType)>0){
            $output["result"]["success"]=true;
            $output["result"]["message"]="Success";
            $output["result"]["errorcode"]=0;
            $output["response"]=$unitType;
        }else{
            $output["result"]["message"]="No unitType found";
            $output["result"]["errorcode"]=1;
        }
       return response()->json($output);
    }
    public function postList(Request $request)
    {
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $user = JWTAuth::parseToken()->authenticate();

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
        $realState = RealEstateAd::skip($start)->take($count);
       //get type
       if($request->type){
         $realState = $realState->where('type',$request->type)->orderBy('real_estate_ad_id', 'DESC');
       }
       if($request->filter) {
         $realState = $realState->where('on_home',$request->filter);
       }
       if($request->keyword) {
         $data=$request->keyword;
         $realState = $realState->where('title','like',"%$data%")->where('is_hide','=','0')->orWhere('description','like',"%$data%")->orderBy('real_estate_ad_id', 'DESC');
       }
       if($request->user) {
         $realState = $realState->where('user_id','=',$user->user_id)->orderBy('real_estate_ad_id', 'DESC');
       }
          $realState=$realState->orderBy('real_estate_ad_id', 'DESC');
          $realState=$realState->get();
          $realstate_original=Directory::where('key','=','realstate_original')->first();
          $realstate_thumb=Directory::where('key','=','realstate_thumb')->first();
          $destinationPath =  url().$realstate_original->path;
          $destinationPathThumb =url().$realstate_thumb->path;
          foreach ($realState as $k=>$v) {
               $realState[$k]['cover_image']=$destinationPath.$realState[$k]['cover_image'];
               $is_owner=0;
               $is_fav=0;
               if($user->user_id==$v->user_id){
                  $is_owner=1;
               }
               $fav=FavoriteRealEstateAd::where('user_id','=',$user->user_id)->where('real_estate_ad_id','=',$v->real_estate_ad_id)->get();
               if(count($fav)>0){
                 $is_fav=1;
               }
               $realState[$k]['is_owner']=$is_owner;
               $realState[$k]['is_fav']=$is_fav;
            foreach ($v->realEstateAdImage as $key=>$value) {
              # co de...
              $v->realEstateAdImage[$key]['originalimg']=$destinationPath.$value->image;
               $v->realEstateAdImage[$key]['thumb']=$destinationPathThumb.$value->image;
            }
          }
          if(count($realState)>0){
            $output["result"]["success"]=true;
            $output["result"]["message"]="Success";
            $output["result"]["user"]=$user->user_id;
            $output["result"]["errorcode"]=0;
            $output["response"]=$realState;
        }else{
            $output["result"]["message"]="No Realstate found";
            $output["result"]["errorcode"]=1;
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
                  'title' => 'required',
                  'description' => 'required',
                  'location' => 'required',
                  'type' => 'required',
                  'number_of_rooms' => 'required',
                  'number_of_bathrooms' => 'required',
                  'price' => 'required',
                  'area' => 'required',
                  'longitude' => 'required',
                  'latitude' => 'required',
                  'owner_name' => 'required',
          //        'cover_image' => 'required',
                  'language' => 'required',
                  'neighbarhood_id' => 'required',
                  'unit_type_id' => 'required',
                  'amenities_id' => 'required',
              ]);
              if ($v->fails())
               {
                  $output["result"]["message"]="Missing Parameters";
                  $output["result"]["errorcode"]=2;
               }else{
                  $RealEstateAd = new RealEstateAd;
                  $RealEstateAd->title = $request->title;
                  $RealEstateAd->description = $request->description;
                  $RealEstateAd->location = $request->location;
                  $RealEstateAd->type = $request->type;
                  $RealEstateAd->number_of_rooms = $request->number_of_rooms;
                  $RealEstateAd->number_of_bathrooms = $request->number_of_bathrooms;
                  $RealEstateAd->price = $request->price;
                  $RealEstateAd->area = $request->area;
                  $RealEstateAd->longitude = $request->longitude;
                  $RealEstateAd->latitude = $request->latitude;
                  $RealEstateAd->language = $request->language;
                  $RealEstateAd->user_id = $request->user_id;
                  $RealEstateAd->owner_name = $request->owner_name;
                  $RealEstateAd->owner_mobile = $request->owner_mobile;
                  $RealEstateAd->owner_email = $request->owner_email;
                  $RealEstateAd->neighbarhood_id = $request->neighbarhood_id;
                  $RealEstateAd->amenities_id = $request->amenities_id ;
                  $RealEstateAd->user_id = $user->user_id ;
                  $RealEstateAd->unit_type_id = $request->unit_type_id;
                  if ($request->hasFile('images')){
                    $realstate_original=Directory::where('key','=','realstate_original')->first();
                    $destinationPath =  public_path().$realstate_original->path;
                    $destinationPathurl =  url().$realstate_original->path;
                    $files = $request->file('images');
                    // Making counting of uploaded images
                    $file_count = count($files);
                    // start count how many uploaded
                    $uploadcount = 0;
                    $i=0;
                    foreach($files as $file) {
                      $rules = array('file' => 'required');
                      $validator = Validator::make(array('file'=> $file), $rules);
                      if($validator->passes()){
                        $realstate_thumb=Directory::where('key','=','realstate_thumb')->first();
                        $destinationPathThumb =public_path().$realstate_thumb->path;
                        $extension = $file->getClientOriginalExtension(); // getting image extensionrealstate_thumb
                        $image_name=explode('.', $file->getClientOriginalName());
                        $fileName =$image_name[0].'_'. time().'.'.$extension; // renameing image
                        $file->move($destinationPath, $fileName); // uploading file to given path
                        $all=$destinationPathurl.$fileName;
                        $thum=$destinationPathThumb.$fileName;
                    //    Image::make($all)->resize(200, 200)->save($thum);
                        //
                        // $img = Image::make($all, array(
                        //   'width' => 100,
                        //   'height' => 100,
                        //   'grayscale' => true
                        // ))->save($thum);
                        if($i==0){
                          $RealEstateAd->cover_image = $fileName;
                          $RealEstateAd->save();
                        }else{
                          $realEstateAdImage=  new RealEstateAdImage;
                          $realEstateAdImage->image=$fileName;
                          $realEstateAdImage->real_estate_ad_id=$RealEstateAd->real_estate_ad_id;
                          $realEstateAdImage->save();
                        }
                        $i++;
                      }
                    }
                  }else{
                    $RealEstateAd->cover_image='default.png';
                    $RealEstateAd->save();
                  }
            if($RealEstateAd){
              $output["result"]["success"]=true;
              $output["result"]["message"]="Success";
              $output["result"]["errorcode"]=0;
              $output["response"]="success";
            }
          }
        return response()->json($output);
    }
    public function postEdit(Request $request)
    {
              $user = JWTAuth::parseToken()->authenticate();
              $output=array();
              $output["result"]=array();
              $output["result"]["success"]=false;
              $output["result"]["message"]="Unknown error";
              $output["result"]["errorcode"]=10;
              $v = Validator::make($request->all(),[
                  'title' => 'required',
                  'description' => 'required',
                  'location' => 'required',
                  'type' => 'required',
                  'number_of_rooms' => 'required',
                  'number_of_bathrooms' => 'required',
                  'price' => 'required',
                  'area' => 'required',
                  'longitude' => 'required',
                  'latitude' => 'required',
                  'language' => 'required',
                  'owner_name' => 'required',
                  'owner_mobile' => 'required',
                  'owner_email' => 'required',
                  'neighbarhood_id' => 'required',
                  'unit_type_id' => 'required',
                  'amenities_id' => 'required',
                  'realstate_id' => 'required',
              ]);
              if ($v->fails()){
                  $output["result"]["message"]="Missing Parameters";
                  $output["result"]["errorcode"]=2;
               }else{
                $RealEstateAd = RealEstateAd::find($request->realstate_id);
                if($RealEstateAd===null){
                    $output["result"]["message"]="No Realstate found";
                    $output["result"]["errorcode"]=1;
                }elseif($RealEstateAd->user_id!=$user->user_id){
                    $output["result"]["message"]="No auth";
                    $output["result"]["errorcode"]=3;
                }else{
                  $RealEstateAd->title = $request->title;
                  $RealEstateAd->description = $request->description;
                  $RealEstateAd->location = $request->location;
                  $RealEstateAd->type = $request->type;
                  $RealEstateAd->number_of_rooms = $request->number_of_rooms;
                  $RealEstateAd->number_of_bathrooms = $request->number_of_bathrooms;
                  $RealEstateAd->price = $request->price;
                  $RealEstateAd->area = $request->area;
                  $RealEstateAd->longitude = $request->longitude;
                  $RealEstateAd->latitude = $request->latitude;
                  $RealEstateAd->language = $request->language;
                  $RealEstateAd->user_id = $user->user_id;
                  $RealEstateAd->owner_name = $request->owner_name;
                  $RealEstateAd->owner_mobile = $request->owner_mobile;
                  $RealEstateAd->owner_email = $request->owner_email;
                  $RealEstateAd->neighbarhood_id = $request->neighbarhood_id;
                  $RealEstateAd->amenities_id = $request->amenities_id ;
                  $RealEstateAd->unit_type_id = $request->unit_type_id;
                  $cover_image = $request->file('cover_image');
                  if($cover_image){
                    $rules = array('file' => 'required');
                    $validator = Validator::make(array('file'=> $cover_image), $rules);
                    $realstate_original=Directory::where('key','=','realstate_original')->first();
                    $destinationPath =  public_path().$realstate_original->path;
                    $extension = $cover_image->getClientOriginalExtension(); // getting image extensionrealstate_thumb
                    $image_name=explode('.', $cover_image->getClientOriginalName());
                    $fileName =$image_name[0].'_'. time().'.'.$extension; // renameing image
                    $cover_image->move($destinationPath, $fileName); // uploading file to given path
                    $RealEstateAd->cover_image = $fileName;
                 }
                  $RealEstateAd->save();
                   $files = $request->file('images');
                    // Making counting of uploaded images
                    $file_count = count($files);
                    // start count how many uploaded
                    $uploadcount = 0;
                    if($file_count>0){
                    foreach($files as $file) {
                      $rules = array('file' => 'required');
                      $validator = Validator::make(array('file'=> $file), $rules);
                      if($validator->passes()){
                        $realstate_original=Directory::where('key','=','realstate_original')->first();
                        $realstate_thumb=Directory::where('key','=','realstate_thumb')->first();
                        $destinationPath =  public_path().$realstate_original->path;
                        $destinationPathurl =  url().$realstate_original->path;
                        $destinationPathThumb =public_path() . $realstate_thumb->path;
                        $extension = $file->getClientOriginalExtension(); // getting image extension
                        $image_name=explode('.', $file->getClientOriginalName());
                        $fileName =$image_name[0].'_'. time().'.'.$extension; // renameing image
                        $file->move($destinationPath, $fileName); // uploading file to given path
                        $all=$destinationPath.$fileName;
                        $thum=$destinationPathThumb.$fileName;
                    //    Image::make($all)->resize(200, 200)->save($thum);
                        $Realpath=$destinationPathThumb.$fileName;
                        $realEstateAdImage=  new RealEstateAdImage;
                        $realEstateAdImage->image=$fileName;
                        $realEstateAdImage->real_estate_ad_id=$RealEstateAd->real_estate_ad_id;
                        $realEstateAdImage->save();
                        $uploadcount ++;
                      }
                  }
                }
                if($RealEstateAd){
                  $output["result"]["success"]=true;
                  $output["result"]["message"]="Success";
                  $output["result"]["errorcode"]=0;
                  $output["response"]="success";
                  //$output["files"]=$files;
                }
              }
        }
        return response()->json($output);
    }
    public function postDelete(Request $request)
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
            $output["result"]["errorcode"]=1;
          }else{
            $real = RealEstateAd::find($request->realstate_id);
            if($real===null){
                $output["result"]["message"]="No Realstate found";
                $output["result"]["errorcode"]=2;
            }elseif($real->user_id!=$user->user_id){
                $output["result"]["message"]="No auth";
                $output["result"]["errorcode"]=3;
            }else{
                  $real->delete();
                  $output["result"]["success"]=true;
                  $output["result"]["message"]="Success";
                  $output["result"]["errorcode"]=0;
            }
          }
        return response()->json($output);
      }
    public function postShow(Request $request)
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
            $realState = RealEstateAd::find($request->realstate_id);
            $realstate_original=Directory::where('key','=','realstate_original')->first();
            $realstate_thumb=Directory::where('key','=','realstate_thumb')->first();
            $destinationPath =  url().$realstate_original->path;
            $destinationPathThumb =url().$realstate_thumb->path;
            $images = $realState->realEstateAdImage;
            $is_owner=0;
            $is_fav=0;
            if($user->user_id==$realState->$user){
               $is_owner=1;
            }
            $realState['cover_image']=$destinationPath.$realState['cover_image'];
            $fav=FavoriteRealEstateAd::where('user_id','=',$user->user_id)->where('real_estate_ad_id','=',$realState->real_estate_ad_id)->get();
            if(count($fav)>0){
              $is_fav=1;
            }
            $realState['is_owner']=$is_owner;
            $realState['is_fav']=$is_fav;
            foreach ($images as  $key=>$value) {
              # code...
              $images[$key]['originalimg']=$destinationPath.$value->image;
              $images[$key]['thumb']=$destinationPathThumb.$value->image;
            }
            if($realState){
                $output["result"]["success"]=true;
                $output["result"]["message"]="Success";
                $output["result"]["errorcode"]=0;
                $output["response"]=$realState;
                $output["response"]["images"]=$images;
            }else{
                $output["result"]["message"]="No Realstate found";
                $output["result"]["errorcode"]=1;
            }
          }
       return response()->json($output);
    }
    public function postImagedelete(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $v = Validator::make($request->all(), [
            'image_id' => 'required',
          ]);
        if ($v->fails())
          {
            $output["result"]["message"]="Missing Parameters";
            $output["result"]["errorcode"]=2;
          }else{
            $real = RealEstateAdImage::find($request->image_id);
            if($real!==null){
              $realstate = RealEstateAd::find($real->real_estate_ad_id);
            }
            if($real===null){
                $output["result"]["message"]="No image found";
                $output["result"]["errorcode"]=1;
            }elseif($realstate->user_id==$user->user_id){
                $output["result"]["message"]="No auth found";
                $output["result"]["errorcode"]=3;
            }else{
                $real=$real->delete();
                $output["result"]["success"]=true;
                $output["result"]["message"]="Success";
                $output["result"]["errorcode"]=0;
                $output["result"]["real"]=$real;
            }
        }
        return response()->json($output);
    }
}
