<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Directory;
use Illuminate\Support\Facades\Validator;
use App\Models\ServicePlace;
use App\Models\ServicePlaceImage;
use App\Models\ServicePlaceReview;
use App\Models\FavoriteServicePlace;
use Input;
//use Validator;
use Redirect;
use Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class ServicePlaceController extends Controller
{

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

              if($request->service_place_id == ""){
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'Sorry No Service Place Id ' , 'errorcode' => '1']]);
                }

              $checkServicePlace = ServicePlace::where("service_place_id" , $request->service_place_id)
                                  ->where('user_id',$user_id)
                                  ->get();
              if(count($checkServicePlace) > 0  ){

                  $servicePlace = ServicePlace::find($request->service_place_id);

                  $logoName = $servicePlace['logo'];

                    // Logo upload
                   $logo = $request->file('logo');

                      $rules = array('file' => 'required|image');
                      $validator = Validator::make(array('file'=> $logo), $rules);
                      if($validator->passes()){
                       $service_logos=Directory::where('key','=','serviceplace_logos')->first();
                       $service_cover=Directory::where('key','=','serviceplace_cover')->first();
                       $logosPath =  public_path(). $service_logos->path;    $extension = $logo->getClientOriginalExtension(); // getting image extension
                        $image_name=explode('.', $logo->getClientOriginalName());
                        $logoName =$image_name[0].'_'. time().'.'.$extension; // renameing image

                      }


                  $servicePlace->service_main_category_id = $request->service_main_category_id;
                  $servicePlace->service_sub_category_id = $request->service_sub_category_id;

                  $servicePlace->title = $request->title;
                  $servicePlace->description = $request->description;
                  $servicePlace->longitude = $request->longitude;
                  $servicePlace->latitude = $request->latitude;
                  $servicePlace->mobile_1 = $request->mobile_1;
                  $servicePlace->mobile_2 = $request->mobile_2;
                  $servicePlace->mobile_3 = $request->mobile_3;
                  $servicePlace->address = $request->address;
                  $servicePlace->neighbarhood_id = $request->neighbarhood_id;
                  $servicePlace->logo = $logoName;
                  $servicePlace->is_approved = 0;
                  $servicePlace->is_hide = 1;
                  $servicePlace->opening_hours = $request->opening_hours;

                  if($servicePlace->service_main_category_id == ""){
                      return response()->json(['result' => ['success' => 'false' , 'message' => 'No Main Category Found' , 'errorcode' => '3']]);
                  }

                  if($servicePlace->service_sub_category_id == ""){
                      return response()->json(['result' => ['success' => 'false' , 'message' => 'No Sub Category Found' , 'errorcode' => '4']]);
                  }

                  if($servicePlace->title == ""){
                      return response()->json(['result' => ['success' => 'false' , 'message' => 'No Title Found' , 'errorcode' => '6']]);
                  }
                  if($servicePlace->neighbarhood_id == ""){
                      return response()->json(['result' => ['success' => 'false' , 'message' => 'No neighbarhood id Found' , 'errorcode' => '7']]);
                  }
                  $servicePlace->save();
                  $logo->move($logosPath, $logoName); // uploading file to given path

                   $files = $request->file('images');
                    // Making counting of uploaded images
                    $file_count = count($files);
                    // start count how many uploaded
                    if($file_count > 0){
                        $uploadcount = 0;
                        foreach($files as $file) {
                          $rules = array('file' => 'required|image');
                          $validator = Validator::make(array('file'=> $file), $rules);
                          if($validator->passes()){
                            $serviceplace_original=Directory::where('key','=','serviceplace_original')->first();
                            $serviceplace_thumb=Directory::where('key','=','serviceplace_thumb')->first();
                            $destinationPathThumb = public_path().$serviceplace_thumb->path;
                            $destinationPath =  public_path(). $serviceplace_original->path;
                            $destinationPathurl =  url(). $serviceplace_original->path;
                            $extension = $file->getClientOriginalExtension(); // getting image extension
                            $image_name=explode('.', $file->getClientOriginalName());
                            $fileName =$image_name[0].'_'. time().'.'.$extension; // renameing image
                            $file->move($destinationPath, $fileName); // uploading file to given path
                            $all=$destinationPathurl.$fileName;
                            $thum=$destinationPathThumb.$fileName;
                          //  Image::make($all)->resize(200, 200)->save($thum);

                           // $img = Image::make($all)->resize(320, 240)->save($thum);
/*                            $img = Image::make($all, array(
                                'width' => 40,
                                'height' => 40,
                                'grayscale' => true
                            ))->save($thum);*/
                            $Realpath=$destinationPathThumb.$fileName;
                            $servicePlaceImage=  new ServicePlaceImage;
                            $servicePlaceImage->image=$fileName;
                            $servicePlaceImage->service_place_id=$servicePlace->service_place_id;
                            $servicePlaceImage->save();
                            $uploadcount ++;
                          }
                        }
                    }

                if($servicePlace){
                  $output["result"]["success"]=true;
                  $output["result"]["message"]="Success";
                  $output["result"]["errorcode"]=0;
                  $output["response"]="success";
                }else{
                $output["result"]["message"]="NoParametersFound";
                $output["result"]["errorcode"]=1;
            }
        }else{
          $output["result"]["message"]="No Service Place with this data";
          $output["result"]["errorcode"]=2;
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
                $logoName='';
                $coverName='';
                 // Logo upload
                 $logo = $request->file('logo');
                    $rules = array('file' => 'required|image');
                    $validator = Validator::make(array('file'=> $logo), $rules);
                    if($validator->passes()){
                      $service_logos=Directory::where('key','=','serviceplace_logos')->first();
                      $service_cover=Directory::where('key','=','serviceplace_cover')->first();
                      $logosPath =  public_path(). $service_logos->path;
                      $extension = $logo->getClientOriginalExtension(); // getting image extension
                      $image_name=explode('.', $logo->getClientOriginalName());
                      $logoName =$image_name[0].'_'. time().'.'.$extension; // renameing image

                      //$logo='';
                    }else{
                      return response()->json(['result' => ['success' => 'false' , 'message' => 'Please Choose Logo' , 'errorcode' => '1']]);
                    }
                $servicePlace = new ServicePlace;
                $servicePlace->service_main_category_id = $request->service_main_category_id;
                $servicePlace->service_sub_category_id = $request->service_sub_category_id;
                $servicePlace->user_id = $user_id;
                $servicePlace->title = $request->title;
                $servicePlace->description = $request->description;
                $servicePlace->longitude = $request->longitude;
                $servicePlace->latitude = $request->latitude;
                $servicePlace->mobile_1 = $request->mobile_1;
                $servicePlace->mobile_2 = $request->mobile_2;
                $servicePlace->mobile_3 = $request->mobile_3;
                $servicePlace->address = $request->address;
                $servicePlace->neighbarhood_id = $request->neighbarhood_id;
                $servicePlace->is_approved = 0;
                $servicePlace->is_hide = 1;
                $servicePlace->on_home = 0;
                $servicePlace->is_featured = 0;
                $servicePlace->total_rate = 0;
                $servicePlace->logo = $logoName;
               // $servicePlace->cover_image = $coverName;
                $servicePlace->opening_hours = $request->opening_hours;
                //return response()->json(["aa" => $servicePlace->service_main_category_id]);

                if($servicePlace->service_main_category_id == ""){
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'No Main Category Found' , 'errorcode' => '3']]);
                }

                if($servicePlace->service_sub_category_id == ""){
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'No Sub Category Found' , 'errorcode' => '4']]);
                }

                if($servicePlace->title == ""){
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'No Title Found' , 'errorcode' => '6']]);
                }
                if($servicePlace->neighbarhood_id == ""){
                    return response()->json(['result' => ['success' => 'false' , 'message' => 'No neighbarhood id Found' , 'errorcode' => '7']]);
                }


                //$servicePlace->save();
                $serviceplace_original=Directory::where('key','=','serviceplace_original')->first();
                $serviceplace_thumb=Directory::where('key','=','serviceplace_thumb')->first();
                $destinationPathThumb = public_path().$serviceplace_thumb->path;
                $destinationPath =  public_path(). $serviceplace_original->path;
                $destinationPathurl =  url(). $serviceplace_original->path;

                //return response()->json(["return " => $servicePlace->service_place_id]);
                 $files = $request->file('images');
                  // Making counting of uploaded images
                  $file_count = count($files);

                  // start count how many uploaded
                  if($file_count > 0){
                      $uploadcount = 0;
                      $i=0;
                      foreach($files as $file) {
                        $rules = array('file' => 'required|image');
                        $validator = Validator::make(array('file'=> $file), $rules);
                        if($validator->passes()){

                          //$serviceplace_thumb=Directory::where('key','=','serviceplace_thumb')->first();
                          //$destinationPathThumb = public_path().$serviceplace_thumb->path;
                          $extension = $file->getClientOriginalExtension(); // getting image extension
                          $image_name=explode('.', $file->getClientOriginalName());
                          $fileName =$image_name[0].'_'. time().'.'.$extension; // renameing image
                          $file->move($destinationPath, $fileName); // uploading file to given path
                          $all=$destinationPathurl.$fileName;
                          $thum=$destinationPathThumb.$fileName;
                      //    Image::make($all)->resize(200, 200)->save($thum);

                          //$thum=$destinationPathThumb.$fileName;
                         //echo $fileName;
                        }
                        //echo $i;

                        if($i==0){
                            $servicePlace->cover_image = $fileName;
                            $logo->move($logosPath, $logoName); // uploading file to given path
                            $servicePlace->save();
                            //echo "true";
                          }else{
                            //echo "false";
                            $servicePlaceImage=  new ServicePlaceImage;
                            $servicePlaceImage->image=$fileName;
                            $servicePlaceImage->service_place_id=$servicePlace->service_place_id;
                            $servicePlaceImage->save();
                            $uploadcount ++;
                          }
                        $i++;
                      }
                      if($servicePlace){
                            $output["result"]["success"]=true;
                            $output["result"]["message"]="Success";
                            //$output["result"]["uploadcount"]=$uploadcount;
                            $output["result"]["errorcode"]=0;
                            $output["response"]="success";
                            //$output["files"]=$files;
                      }else{
                          $output["result"]["message"]="Error in insert to data Base";
                          $output["result"]["errorcode"]=1;
                      }

                  }else{
                          $output["result"]["message"]="You Must Choose One Image At Least";
                          $output["result"]["errorcode"]=1;
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

        $servicePlace = ServicePlace::where("service_place_id" ,$Request->service_place_id )
                        ->where('user_id' , $user_id)
                      ->get();

        if(count($servicePlace) > 0){
            $real = ServicePlace::find($Request->service_place_id)->delete();
              if(!$real){
                    $output["result"]["message"]="NoParametersFound";
                    $output["result"]["errorcode"]=1;

              }else{
                    $output["result"]["success"]=true;
                    $output["result"]["message"]="Success";
                    $output["result"]["errorcode"]=0;
              }

        }else{
            $output["result"]["message"]="NoParametersFound";
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
        $image=ServicePlace::where('is_featured','=','1')->take(5)->get();
        $realstate_original=Directory::where('key','=','serviceplace_original')->first();
        $realstate_thumb=Directory::where('key','=','serviceplace_thumb')->first();
        $destinationPath =  url().$realstate_original->path;
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

    public function postList(Request $request)
    {
           //
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

            $whereCond=0;

            $servicePlace = ServicePlace::skip($start)->take($count)
//            ->select("service_place_id","logo","title","cover_image",'user_id')
            ->select("service_place_id","logo","title","service_sub_category_id");

           if($request->filter) {
            $servicePlace = $servicePlace->where('on_home',$request->filter);
           }

           if($request->neighbarhood_id) {
            $servicePlace = $servicePlace->where('neighbarhood_id',$request->neighbarhood_id);
           }
           /*if($request->order){
                if($request->order == 1){
                 $servicePlace = $servicePlace->orderBy('created_at','DESC');
                }
                if($request->order == 2){
                 $servicePlace = $servicePlace->orderBy('title','ASC');
                }
           }*/
           if(trim($request->user_id) != "" ) {
            
              $user = JWTAuth::parseToken()->authenticate();

              $user_id = $user->user_id;
             $servicePlace = $servicePlace->where('user_id'  , '=' , $user_id );
             $whereCond=1;
           }
           if($request->keyword) {
             $data=$request->keyword;
             $servicePlace = $servicePlace->where('title','like',"%$data%");
           }
           if($whereCond == 0){
                            $servicePlace = $servicePlace->where('is_hide',0)
                                    ->where('is_approved' , 1)
                                    ->where("service_sub_category_id" , $request->service_sub_category_id);
           }

            $servicePlace = $servicePlace->orderBy('service_place_id','DESC');
            $servicePlace=$servicePlace->get();
            $service_logos=Directory::where('key','=','serviceplace_logos')->first();
            $service_cover=Directory::where('key','=','serviceplace_cover')->first();
            $cover = url().$service_cover->path;
            $logo =url().$service_logos->path;
            $serviceplace_original=Directory::where('key','=','serviceplace_original')->first();
            $serviceplace_thumb=Directory::where('key','=','serviceplace_thumb')->first();
            $destinationPath =url(). $serviceplace_original->path;
            $destinationPathThumb =url().$serviceplace_thumb->path;
            foreach ($servicePlace as $k=>$v) {
                 $servicePlace[$k]['logo']=$logo.$servicePlace[$k]['logo'];
                 //$servicePlace[$k]['cover_image']=$cover.$servicePlace[$k]['cover_image'];
                /*foreach($v->servicePlaceImage as  $key=>$value) {
                     $img= $v->servicePlaceImage[$key]['image'];
                     $v->servicePlaceImage[$key]['image']= $destinationPath.$img;
                      $v->servicePlaceImage[$key]['thumb']= $destinationPathThumb.$img;
                }*/
            }
            /*foreach ($servicePlace as $data) {
                $review=array();
                foreach ($data->servicePlaceReview as $v) {
                }
            }*/
            if(count($servicePlace) > 0){
                $output["result"]["success"]=true;
                $output["result"]["message"]="Success";
                $output["result"]["errorcode"]=0;
                $output["response"]=$servicePlace;
            }else{
                $output["result"]["message"]="No Service Place found";
                $output["result"]["errorcode"]=1;
            }
       

       return response()->json($output);
           
    }

    public function postImagedelete(Request $request)
    {
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
        $real = ServicePlaceImage::find($request->image_id)->delete();
        if($real){
            $output["result"]["success"]=true;
            $output["result"]["message"]="Success";
            $output["result"]["errorcode"]=0;
            $output["result"]["real"]=$real;
        }else{
            $output["result"]["message"]="No servicePlace found";
            $output["result"]["errorcode"]=1;
        }
        return response()->json($output);
    }


    public function postShow(Request $request){

        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;

        $v = Validator::make($request->all(), [
              'service_place_id' => 'required',
          ]);
        if ($v->fails())
          {
            $output["result"]["message"]="Missing Parameters";
            $output["result"]["errorcode"]=2;
          }else{


            $user = JWTAuth::parseToken()->authenticate();
            $user_id = $user->user_id;

            $servicePlace = ServicePlace::where("service_place_id" ,'=', $request->service_place_id)->get();
            $checkIsReview = ServicePlaceReview::where('service_place_id' , $request->service_place_id)
                              ->where('user_id' , $user_id)->get();
            $checkisFav = FavoriteServicePlace::where('service_place_id' , $request->service_place_id)
                              ->where('user_id' , $user_id)->get();
            $is_review='';
            $is_fav='';
            if(count($checkIsReview) > 0){
              $is_review= 1;
            }else{
              $is_review = 0;
            }

            if(count($checkisFav) > 0){
              $is_fav= 1;
            }else{
              $is_fav = 0;
            }
            $servicePlace[0]['is_review'] = $is_review;
            $servicePlace[0]['is_favorite'] = $is_fav;

            $service_logos=Directory::where('key','=','serviceplace_logos')->first();
            $service_cover=Directory::where('key','=','serviceplace_cover')->first();
            $logo =url().$service_logos->path;
            $cover =url().$service_cover->path;
            $serviceplace_original=Directory::where('key','=','serviceplace_original')->first();
            $serviceplace_thumb=Directory::where('key','=','serviceplace_thumb')->first();
            $destinationPath =url(). $serviceplace_original->path;
            $destinationPathThumb =url().$serviceplace_thumb->path;
            if(count($servicePlace) > 0){
                $img=$servicePlace[0]->logo;
                 $cimg=$servicePlace[0]->image_cover;
                 $servicePlace[0]->logo=$logo.$img;
                 $servicePlace[0]->cover_image=$cover.$cimg;
               $images=ServicePlaceImage::where('service_place_id','=',$request->service_place_id)->get();
                foreach ($images as $key => $value) {
                   $images[$key]['origninal']=$destinationPath.$images[$key]['image'];
                   $images[$key]['thumb']=$destinationPathThumb.$images[$key]['image'];
               }

                $review=ServicePlaceReview::where('service_place_id','=',$request->service_place_id)
                                            ->where('is_hide' , 0)
                                            ->get();

                foreach ($review as $rev){
                   $rev->review;
                   $rev->user;
                }

/*
                foreach($favorites as $fav){
                  $fav->servicePlace;
                  $fav->user;
                }*/



                //$servicePlace['review']=$servicePlace->servicePlaceReview ;
                $output["result"]["success"]=true;
                $output["result"]["message"]="Success";
                $output["result"]["errorcode"]=0;
                $output["response"]['serviceplace']=$servicePlace;
                $output["response"]['images']=$images;
                $output["response"]['review']=$review;


            }else{
                $output["result"]["message"]="No Service Place found";
                $output["result"]["errorcode"]=1;
            }
          }
       return response()->json($output);
    }
}   
