<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Neighbarhood;

class NeighbarhoodController extends Controller {

	public function __construct()
	{
		$this->middleware('chkAdminAuth');

	}

	public function getIndex()
	{
		$neighbarhood = Neighbarhood::all();
		return view("backend/neighbarhood/index",['neighbarhood' => $neighbarhood]);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getAdd(Request $request)
	{

		$url=  $request->url();
		$neighbarhood = new Neighbarhood ;
		$action=   $url.'/../store';
		return view("backend/neighbarhood/add",['neighbarhood' => $neighbarhood,'action'=>$action]);


	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore(Request $request)
	{
		 $neighbarhood =new Neighbarhood;
		 $neighbarhood->title_ar=$request->title_ar;
		 $neighbarhood->title_en=$request->title_en;
		 $neighbarhood->save();
			return redirect('/backend/neighbarhood/index');


	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getEdit($id,Request $request)
	{

				$url=  $request->url();
				$neighbarhood = Neighbarhood::find($id) ;
				$action=   $url.'/../../update';
				return view("backend/neighbarhood/add",['neighbarhood' => $neighbarhood,'action'=>$action]);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate(Request $request)
	{
		$neighbarhood = Neighbarhood::find($request->neighbarhood_id);
		$neighbarhood->title_ar=$request->title_ar;
		$neighbarhood->title_en=$request->title_en;
		$neighbarhood->save();
		 return redirect('/backend/neighbarhood/index');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDestroy($id)
	{
		$neighbarhood = Neighbarhood::find($id);
		$neighbarhood->delete();
		return redirect('/backend/neighbarhood/index');

	}

}
