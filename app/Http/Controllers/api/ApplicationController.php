<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Lookup;
use App\Models\AppConfig;
use App\Models\Directory;
use App\Models\RealEstateAd;
use App\Models\RealEstateAdImage;
use App\Models\ServicePlaceImage;
use App\Models\ServicePlace;

class ApplicationController extends Controller {

    public function getLookups(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $lookups = Lookup::where(function($query) use ($request) {
                    if ($request->has("keyword")) {
                        $query->where("lookup_key", $request->get("keyword"));
                    }
                })
                ->get(["lookup_code as lookup_code", "lookup_key as key", "look_value as value"]);
        if (count($lookups) > 0) {
//            $lookups["total"] = count($lookups);
            $output["result"]["success"] = true;
            $output["result"]["message"] = "Success";
            $output["result"]["errorcode"] = 0;
            $output["response"]["total"] = count($lookups);
            $output["response"]["lookups"] = $lookups;
        } else {
            $output["result"]["message"] = "No lookups found";
            $output["result"]["errorcode"] = 2;
        }
        return $output;
    }

    public function getAppconfig(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $configs = AppConfig::where(function($query) use ($request) {
                    if ($request->has("key")) {
                        $query->where("key", $request->get("key"));
                    }
                })
                ->get(["key", "value"]);
        if (count($configs) > 0) {
            $output["result"]["success"] = true;
            $output["result"]["message"] = "Success";
            $output["result"]["errorcode"] = 0;
            $output["response"]["total"] = count($configs);
            $output["response"]["appconfig"] = $configs;
        } else {
            $output["result"]["message"] = "No configurations found";
            $output["result"]["errorcode"] = 2;
        }
        return $output;
        return "Appconfig";
    }
    public function postOnhome(){
      $output = array();
      $output["result"] = array();
      $output["result"]["success"] = false;
      $output["result"]["message"] = "Unknown error";
      $output["result"]["errorcode"] = 10;
      $realstate_original=Directory::where('key','=','realstate_original')->first();
      $destinationPath =  url().$realstate_original->path;
      $service_cover=Directory::where('key','=','serviceplace_cover')->first();
      $cover =   url().$service_cover->path;

      $realEstateAd=RealEstateAd::where("is_hide",'=','0')->where('on_home','=','1')->take('3')->get();
      $servicePlace=ServicePlace::where("is_hide",'=','0')->where('on_home','=','1')->where('is_approved','=','1')->take('3')->get();
       $data= array();
       $data['realstate']=array();
        foreach ($realEstateAd as $key => $value) {
          $title=$value->title;
          $img=$destinationPath.$value->cover_image;
          $obj=['title'=>$title,'image'=>$img];
          array_push($data['realstate'],$obj);
        }
        $data['servicePlace']=array();
        foreach ($servicePlace as $key => $value) {
          $title=$value->title;
          $img=$cover.$value->cover_image;
          $obj=['title'=>$title,'image'=>$img];
          array_push($data['servicePlace'],$obj);
        }
      // use App\Models\RealEstateAd;
      // use App\Models\RealEstateAdImage;
      // use App\Models\ServicePlaceImage;
      // use App\Models\ServicePlace;

      if ($data) {
          $output["result"]["success"] = true;
          $output["result"]["message"] = "Success";
          $output["result"]["errorcode"] = 0;
          $output["response"]["slider"] = $data;
      } else {
          $output["result"]["message"] = "No data found";
          $output["result"]["errorcode"] = 2;
      }
      return $output;

    }
}
