<?php

namespace App\Http\Controllers\api;
use Carbon\Carbon;
use Faker\Provider\DateTime;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use App\Models\ReportReason;

use App\Http\Controllers\Controller;
class ReportController extends Controller {

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('jwt.auth');
    }

//add report
  public function postAdd(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;
//'reporter_id','created_at','reported_id','reported_type_id','report_reason_id
        $rules = [
            'reported_id' => 'required|integer ',
            'reported_type_id' => 'required|integer|exists:reported_type',
            'report_reason_id' =>'required|integer'
        ];

        $input = Input::only(
            'reported_id',
            'reported_type_id',
            'report_reason_id',
            'report_message'

        );

        

        $validator = Validator::make($input, $rules);
        if ($validator->fails()){

            $output["result"]["message"]=$validator->errors();

            if ($output["result"]["message"]->has('reported_id')||$output["result"]["message"]->has('reported_type_id')||$output["result"]["message"]->has('report_reason_id')){
                $output["result"]["errorcode"]=10;
            } else{
                $output["result"]["errorcode"]=1;
            }

        }else{
            $report=Report::where('reported_id',$request['reported_id'])->where('reporter_id',$user->user_id)->first();
                    if (!$report){
                        $request['created_at']=Carbon::now();
                        //$request['report_message']= $request['report_message'];
                        $user->reports()->create($request->all());

                        //output succsses
                        $output["result"]["success"] = true;
                        $output["result"]["message"] = "user added report successfully ";
                        $output["result"]["errorcode"] = 0;
                    }else{
                        $output["result"]["message"] = "You can't report thing more than one time ";
                    }







        }
        return $output;
    }


    public function postReasonlist(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;

        $reasons = ReportReason::all(["report_reason_id as reason_id", "title_en as reason_en", "title_ar as reason_ar"]);

        if (count($reasons) > 0) {
            $output["result"]["success"] = true;
            $output["result"]["message"] = "Success";
            $output["result"]["errorcode"] = 0;
            $output["response"]["total"] = count($reasons);
            $output["response"]["reasons"] = $reasons;
        } else {
            $output["result"]["message"] = "No reasons found";
            $output["result"]["errorcode"] = 2;
        }

        return $output;
    }

    

}
