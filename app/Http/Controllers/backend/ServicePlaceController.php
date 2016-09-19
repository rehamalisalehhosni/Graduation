<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ServicePlace;
use App\Models\ServicePlaceImage;
use App\Models\Directory;
use App\Models\User;
use App\Models\ServicePlaceReview;
use App\Models\Report;
use App\Models\UnitType;
use App\Models\ServiceMainCategory;
use Mapper;

class ServicePlaceController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function __construct()
	{
		$this->middleware('chkAdminAuth');

	}


	public function getIndex()
	{
			$servicePlaceCategory = ServiceMainCategory::where('is_main' , 1)->get();

	  		$servicePlace = ServicePlace::orderBy('service_place_id', 'desc')->paginate(15);
			//$servicePlace->setPath("./serviceplace/index");/backend/
			$path=url("/backend/serviceplace/index");
			$servicePlace->setPath($path);
			$servicePlace_thumb=Directory::where('key','=','serviceplace_original')->first();

			return view("backend/serviceplace/index",['serviceplace' => $servicePlace ,'image_path'=>$servicePlace_thumb->path , 'mainCat' => $servicePlaceCategory]);
	}

	public function getSearch(Request $request)
	{

			$query= $request->search;
			$status= $request->status;
			$menu= $request->menu;

			$param=array();

			$servicePlaceCategory = ServiceMainCategory::where('is_main' , 1)->get();

			$servicePlace = ServicePlace::orderBy('service_place_id', 'desc');//->paginate(15);


			// Check if there is Status Condition

			if($menu > 0){
				$servicePlace = $servicePlace->where('service_main_category_id' , $menu);
				$param['menu'] = $menu;
				//$param = array_add($param, 'menu', $menu);
				//return response()->json($param);
				//$param =  $menu;
				//var_dump($param);
				if($request->submenu){
					$subMenu = $request->submenu;
					if($subMenu != 0){
						$servicePlace = $servicePlace->where('service_sub_category_id' , $subMenu);
						$param['submenu'] =$subMenu;
						//$param = array_add($param, 'submenu', $subMenu);
					}
				}
			}



			// Check if there is Status Condition

			if($status == 1 or $status == 2){
				$servicePlace = $servicePlace->where('is_approved' , $status);
				$param['status'] =$status;
			}

			// Check if there is Search  Condition

			if(trim($query) != ""){
				$param['search'] =$query;
				$servicePlace = $servicePlace->where('title','like',"%$query%");
			}

			$servicePlace=$servicePlace -> paginate(3);

	 		$servicePlace->setPath("./search");
			$servicePlace_thumb=Directory::where('key','=','serviceplace_original')->first();

			return view("backend/serviceplace/search",['serviceplace' => $servicePlace ,'image_path'=>$servicePlace_thumb->path , 'mainCat' => $servicePlaceCategory , 'param' => $param]);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getShow($id)
	{



		$servicePlace = ServicePlace::find($id);

		Mapper::map($servicePlace->latitude , $servicePlace->longitude , ['zoom' => 18, 'markers' => ['title' => $servicePlace->title, 'animation' => 'DROP']]);
			/*
			Mapper::map( $servicePlace->latitude , $servicePlace->longitude  , [ 'zoom' => 18]);
	        $content = "aaaaaaaaaaaaa";
	        Mapper::informationWindow($servicePlace->latitude , $servicePlace->longitude,"bbbabb" , ['markers' => ['title' => 'Title']] );
	        Mapper::marker(  $servicePlace->latitude , $servicePlace->longitude );*/
	        //Mapper::marker(  31.0508614 , 31.39592 );


			$servicePlace_thumb=Directory::where('key','=','serviceplace_original')->first();
			return view("backend/serviceplace/show",['serviceplace' => $servicePlace ,'image_path'=>$servicePlace_thumb->path]);
	}

	public function postSubmenu(Request $request)
	{
		$output=array();
        //$output["result"]=array();
		//echo $request->menu;
		$servicePlaceCategory = ServiceMainCategory::where('is_main' , 0) -> where('main_category' , $request->menu)->get();

		$output["response"]=$servicePlaceCategory;

		return response()->json($output);

	}




	// Unhide Review

	public function getApprove($id)
	{
		$servicePlace = ServicePlace::find($id);
		$servicePlace ->is_hide=0;
		$servicePlace ->is_approved=1;
		$servicePlace -> save();

		//Notifications
                $notification = new PushNotificationController;
                $owner_id = $servicePlace->User->user_id;
                $sender_id = "Admin";
                $type = "ServicePlaceApproved";
                $type_id = $id;
                if ($owner_id != $sender_id) {
                    $notification->getHandleNotification($owner_id, $sender_id, $type, $type_id);
                }

		$servicePlaceCategory = ServiceMainCategory::where('is_main' , 1)->get();
		$servicePlace = ServicePlace::orderBy('service_place_id', 'desc')->paginate(15);
		$servicePlace->setPath("./index");
		$servicePlace_thumb=Directory::where('key','=','serviceplace_original')->first();

   		return view("backend/serviceplace/index",['serviceplace' => $servicePlace ,'image_path'=>$servicePlace_thumb->path , 'mainCat' => $servicePlaceCategory]);

		/*
		$comments = ServicePlaceReview::Where('service_place_id','=',$servicePlaceReview->service_place_id)->orderBy('service_place_review_id', 'desc')->get();
		return view("backend/serviceplace/comments",['comments' => $comments ]);
*/
	}

	/*
		Service Place Reviews  + hide review  + unhide Review
	*/


	public function getReviews($id)
	{
	  $comments = ServicePlaceReview::Where('service_place_id','=',$id)->orderBy('service_place_review_id', 'desc')->get();
		return view("backend/serviceplace/comments",['comments' => $comments ]);

	}

	// Unhide Review

	public function getUnhidereview($id)
	{
		$servicePlaceReview = ServicePlaceReview::find($id);
		$servicePlaceReview->is_hide=0;
		$servicePlaceReview->save();
		$comments = ServicePlaceReview::Where('service_place_id','=',$servicePlaceReview->service_place_id)->orderBy('service_place_review_id', 'desc')->get();
		return view("backend/serviceplace/comments",['comments' => $comments ]);

	}


	// hide Review

	public function getHidereview($id)
	{
		$servicePlaceReview = ServicePlaceReview::find($id);
		$servicePlaceReview->is_hide=1;
		$servicePlaceReview->save();
		$comments = ServicePlaceReview::Where('service_place_id','=',$servicePlaceReview->service_place_id)->orderBy('service_place_review_id', 'desc')->get();
		return view("backend/serviceplace/comments",['comments' => $comments ]);

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */


	public function getReports($id)
	{
		//Service Place Reports
	  $reports = Report::Where('reported_type_id','=',1)->where('reported_id','=',$id)->orderBy('report_id', 'desc')->get();
		return view("backend/serviceplace/reports",['reports' => $reports ]);

	}
	public function getReviewsreports($id)
	{
		//Service Place Reports
	  $reports = Report::Where('reported_type_id','=',2)->where('reported_id','=',$id)->orderBy('report_id', 'desc')->get();
		return view("backend/serviceplace/reports",['reports' => $reports ]);

	}



	public function postSaveoptions(Request $request)
	{
		$servicePlace = ServicePlace::find($request->serviceplace_id);
		 if($request->on_home){
    	$servicePlace->on_home=$request->on_home;
		}else{
			$servicePlace->on_home=0;

		}

		if($request->is_featured){
    	$servicePlace->is_featured=$request->is_featured;
		}else{
			$servicePlace->is_featured=0;
		}

		if($request->is_hide){
    	$servicePlace->is_hide=$request->is_hide;
		}else{
			$servicePlace->is_hide=0;
		}


		$servicePlace->save();
		return redirect("/backend/serviceplace/show/$servicePlace->service_place_id");
	}



}
