<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;


use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ServiceMainCategory;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Directory;

class ServiceCategoryController extends Controller
{
    //

    public function __construct() {
        $this->middleware('guest' , ['except' => 'postList']);
        $this->middleware('jwt.auth' , ['except' => 'postList']);
    }

    public function postList(Request $request)
    {

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

       // $is_main=0;
        $serviceCategory= DB::table('service_main_category');


            if($request->main_category) {

	        	$serviceCategory = $serviceCategory->where('main_category',$request->main_category);
                $serviceCategory=$serviceCategory->get();

                foreach ($serviceCategory as $k=>$v) {
                    $countSub= DB::table('service_place')->where('service_sub_category_Id' , $serviceCategory[$k]->service_main_category_Id)->get();      
                     $serviceCategory[$k]->services = count($countSub);
                } 


	        }else{
                $serviceCategory = $serviceCategory->where('is_main'  , 1);
                $serviceCategory = $serviceCategory->select("title_ar" , "logo" , "title_en" , "service_main_category_id");
                $serviceCategory=$serviceCategory->get();

                foreach ($serviceCategory as $k=>$v) {
                    $countSub= DB::table('service_main_category')->where('main_category' , $serviceCategory[$k]->service_main_category_id)->get();
                     $serviceCategory[$k]->subcats = count($countSub);
                }
            }




            $service_logos=Directory::where('key','=','serviceplace_category')->first();
            $logo =url().$service_logos->path;



          foreach ($serviceCategory as $k=>$v) {
                 $serviceCategory[$k]->logo = $logo.'/'.$serviceCategory[$k]->logo;
            }





          if(count($serviceCategory)>0){
            $output["result"]["success"]=true;
            $output["result"]["message"]="Success";
            $output["result"]["errorcode"]=0;
            $output["response"]=$serviceCategory;
        }else{
            $output["result"]["message"]="No  Favorite Service Category found";
            $output["result"]["errorcode"]=1;
        }


       return response()->json($output);
    }
}
