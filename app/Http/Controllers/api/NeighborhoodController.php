<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Neighbarhood;

class NeighborhoodController extends Controller {

    public function postList(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;

        $list = Neighbarhood::where(function($query) use ($request) {
                    if ($request->has("keyword")) {
                        $query->where("title_ar", "LIKE", '%'.$request->get('keyword').'%')
                        ->orWhere("title_en", "LIKE", '%'.$request->get('keyword').'%');
                    }
                })
                ->get(["neighbarhood_id as id", "title_ar", "title_en"]);

        if (count($list) > 0) {
            $output["result"]["success"] = true;
            $output["result"]["message"] = "Success";
            $output["result"]["errorcode"] = 0;
            $output["response"]["total"] = count($list);
            $output["response"]["neighborhoods"] = $list;
        } else {
            $output["result"]["message"] = "No neighborhoods found";
            $output["result"]["errorcode"] = 2;
        }

        return $output;
    }

}
