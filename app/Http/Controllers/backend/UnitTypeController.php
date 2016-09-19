<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\UnitType;


class UnitTypeController extends Controller {

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
		$unitType = UnitType::all();
	  return view("backend/unit_type/index",['unitType' => $unitType]);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getAdd(Request $request)
	{

    $url=  $request->url();
		$unitType = new UnitType ;
    $action=   $url.'/../store';
		return view("backend/unit_type/add",['unitType' => $unitType,'action'=>$action]);


	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore(Request $request)
	{
     $unit =new UnitType;
		 $unit->title_ar=$request->title_ar;
		 $unit->title_en=$request->title_en;
     $unit->save();
   		return redirect('/backend/unittype/index');


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
				$unitType = UnitType::find($id) ;
		    $action=   $url.'/../../update';
				return view("backend/unit_type/add",['unitType' => $unitType,'action'=>$action]);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate(Request $request)
	{
		$unit = UnitType::find($request->unit_type_id);
		$unit->title_ar=$request->title_ar;
		$unit->title_en=$request->title_en;
		$unit->save();
		 return redirect('/backend/unittype/index');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDestroy($id)
	{
		$unit = UnitType::find($id);
		$unit->delete();
		return redirect('/backend/unittype/index');

	}

}
