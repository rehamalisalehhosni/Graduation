<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RealEstateAd;
use App\Models\ServicePlace;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatisticController extends Controller {

		public function __construct()
		{
			$this->middleware('chkAdminAuth');

		}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$users=User::all();
		$deactiveUser=User::where('is_active','=','0');
		$real=RealEstateAd::all();
		$realHidden=RealEstateAd::where('is_hide', '=','1');
		$realShow=RealEstateAd::where('is_hide', '!=','1');
		$service=ServicePlace::all();
		$serviceApprove=ServicePlace::where('is_approved', '=', '1');
		$serviceHidden=ServicePlace::where('is_hide', '=', '1');
		$discussion=Discussion::all();
		$discussionHide=Discussion::where('is_hide', '=','1');

		return view('backend.statistic.index',['users'=>count($users),'deactiveUser'=>count($deactiveUser),'real'=>count($real),'realHidden'=>count($realHidden),'realShow'=>count($realShow),'service'=>count($service),'serviceApprove'=>count($serviceApprove),'serviceHidden'=>count($serviceHidden),'discussion'=>count($discussion),'discussionHide'=>count($discussionHide)]);

	}

}
