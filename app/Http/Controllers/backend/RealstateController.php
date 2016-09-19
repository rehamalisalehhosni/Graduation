<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\RealEstateAd;
use App\Models\RealEstateAdImage;
use App\Models\Directory;
use App\Models\User;
use App\Models\RealEstateAdComment;
use App\Models\Report;
use App\Models\UnitType;

class RealstateController extends Controller {

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
	  	$realState = RealEstateAd::where('is_hide','=','0')->orderBy('real_estate_ad_id', 'desc')->paginate(15);
			$path=url('/backend/realstate/index');
			$realState->setPath($path);
			$realstate_thumb=Directory::where('key','=','realstate_original')->first();

	   return view("backend/realstate/index",['realState' => $realState ,'image_path'=>$realstate_thumb->path]);
	}
	public function getHidden()
	{
	  	$realState = RealEstateAd::where('is_hide','=','1')->orderBy('real_estate_ad_id', 'desc')->paginate(15);
			$path=url('/backend/realstate/hidden');
			$realState->setPath($path);
			$realstate_thumb=Directory::where('key','=','realstate_original')->first();

	   return view("backend/realstate/hidden",['realState' => $realState ,'image_path'=>$realstate_thumb->path]);
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getShow($id)
	{
		$realState = RealEstateAd::find($id);
		$realstate_thumb=Directory::where('key','=','realstate_original')->first();
		return view("backend/realstate/show",['realState' => $realState ,'image_path'=>$realstate_thumb->path]);

	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDestroy($id)
	{
		//
		$realState = RealEstateAd::find($id);
		$realState->is_hide=1;
		$realState->save();
		return redirect('/backend/realstate/index');

	}
	public function getDisplay($id)
	{
		//
		$realState = RealEstateAd::find($id);
		$realState->is_hide=0;
		$realState->save();
		return redirect('/backend/realstate/index');

	}
	public function getDestroycomment($id)
	{

		$realStatecomment = RealEstateAdComment::find($id);
		$realStatecomment->is_hide=1;
		$realStatecomment->save();
		return redirect("/backend/realstate/comments/$realStatecomment->real_estate_ad_id");

	}
	public function getComments($id)
	{
	  $comments = RealEstateAdComment::Where('real_estate_ad_id','=',$id)->Where('is_hide','=','0')->orderBy('real_estate_ad_comment_id', 'desc')->get();
		return view("backend/realstate/comments",['comments' => $comments ]);

	}
	public function getReports($id)
	{
		//real_estate_ad 6
	  $reports = Report::Where('reported_type_id','=',6)->where('reported_id','=',$id)->orderBy('report_id', 'desc')->get();
		return view("backend/realstate/reports",['reports' => $reports ]);

	}
	public function getCommentreports($id)
	{
		//real_estate_ad 6
	  $reports = Report::Where('reported_type_id','=',7)->where('reported_id','=',$id)->orderBy('report_id', 'desc')->get();
		return view("backend/realstate/reports",['reports' => $reports ]);

	}
	public function getSearch(Request $request)
	{
		$path=url('/backend/realstate/search');

		$query= $request->search;
		$realState = RealEstateAd::where('is_hide','=','0')
		 													 ->where('title','like',"%$query%")
		 													 ->orwhere('description','like',"%$query%")
		 													 ->orderBy('real_estate_ad_id', 'desc')->paginate(15);
	 $realState->setPath($path);
	 $realstate_thumb=Directory::where('key','=','realstate_original')->first();

	  return view("backend/realstate/search",['realState' => $realState ,'image_path'=>$realstate_thumb->path,'query'=>$query]);

	}
	public function postSaveoptions(Request $request)
	{
		$realState = RealEstateAd::find($request->realstate_id);
		 if($request->on_home){
    	$realState->on_home=$request->on_home;
		}else{
			$realState->on_home=0;

		}
		 if($request->is_featured){
    	$realState->is_featured=$request->is_featured;
		}else{
			$realState->is_featured=0;
		}
		$realState->save();
		return redirect("/backend/realstate/show/$realState->real_estate_ad_id");
	}

	public function postSaveimages(Request $request)
	{
		$primary=$request->is_primary;
		RealEstateAdImage::where('real_estate_ad_id', '=',$request->realstate_id )
												->update(array('is_primary' => 0));
		RealEstateAdImage::where('real_estate_ad_id', '=',$request->realstate_id )
		 									 ->where('real_estate_ad_image_id','=',$request->is_primary)->update(array('is_primary' => 1));

		return redirect("/backend/realstate/show/$request->realstate_id");
	}


}
