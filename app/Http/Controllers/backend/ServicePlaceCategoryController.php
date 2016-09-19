<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ServiceMainCategory;
use App\Models\Directory;
use Redirect;
use Image;
use Illuminate\Support\Facades\Validator;

class ServicePlaceCategoryController extends Controller {

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
		$unitType = ServiceMainCategory::all();
		$servicePlaceCategory_thumb=Directory::where('key','=','serviceplace_category')->first();
	  	return view("backend/serviceplacecategory/index",['category' => $unitType , 'image_path'=>$servicePlaceCategory_thumb->path ,]);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getAdd(Request $request)
	{

		$mainCat = ServiceMainCategory::where("is_main" , 1)->get();
    	$url=  $request->url();
		$cat = new ServiceMainCategory ;
    	$action=   $url.'/../store';
		return view("backend/serviceplacecategory/add",['cat' => $cat,'action'=>$action , 'main' => $mainCat]);


	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function postStore(Request $request)
	{
		//echo "aaaaaa";
     	$mainCat =new ServiceMainCategory;
     	
     	$logo = $request->file('logo');
                    
	    $rules = array('file' => 'required|image'); 
	    $validator = Validator::make(array('file'=> $logo), $rules);
	     if($validator->passes()){
	       
	       $servicePlaceCategory_thumb=Directory::where('key','=','serviceplace_category')->first();

	       $logosPath =  public_path(). $servicePlaceCategory_thumb->path;
	       //return $request->menu;
	       $extension = $logo->getClientOriginalExtension(); // getting image extension
	        $image_name=explode('.', $logo->getClientOriginalName());
	        $logoName =$image_name[0].'_'. time().'.'.$extension; // renameing image
	        $logo->move($logosPath, $logoName); // uploading file to given path

	        $mainCat->title_ar=$request->title_ar;
			$mainCat->title_en=$request->title_en;
			$mainCat->main_category= NULL;
			if($request->menu != 0){
				$mainCat->main_category=$request->menu;	
			}

			//$mainCat->main_category=$request->menu;
			$mainCat->logo=$logoName;
			$mainCat->is_main = 0;
			if($mainCat->main_category == 0){
				$mainCat->is_main = 1;
			}
	     	 
	     	$mainCat->save();
	    	return redirect('/backend/serviceplacecategory/index');

	      }else{
	      	echo "Sorry No Image Found";
	      } 
			}



	

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getEdit($id,Request $request)
	{


			$mainCat = ServiceMainCategory::where("is_main" , 1)->get();
	    	
	    	$url=  $request->url();
			$cat = ServiceMainCategory::find($id);

	    	$action=   $url.'/../../update';
			return view("backend/serviceplacecategory/add",['cat' => $cat,'action'=>$action , 'main' => $mainCat]);


	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function postUpdate(Request $request)
	{

		
		$cat = ServiceMainCategory::find($request->service_main_category_id);
		$logo = $request->file('logo');

	    $logoName = $cat['logo'];

	   $logo = $request->file('logo');
                    
	    $rules = array('file' => 'required|image'); 
	    $validator = Validator::make(array('file'=> $logo), $rules);
	    if($validator->passes()){
	       
	       $servicePlaceCategory_thumb=Directory::where('key','=','serviceplace_category')->first();

	       $logosPath =  public_path(). $servicePlaceCategory_thumb->path;
	       //return $request->menu;
	       $extension = $logo->getClientOriginalExtension(); // getting image extension
	        $image_name=explode('.', $logo->getClientOriginalName());
	        $logoName =$image_name[0].'_'. time().'.'.$extension; // renameing image
	        $logo->move($logosPath, $logoName); // uploading file to given path
	      }

	    $cat->title_ar =$request->title_ar;
		$cat->title_en = $request->title_en;
		
		if($request->menu != 0){
			$cat->main_category=$request->menu;	
		}else{
			$cat->main_category = NULL;	
		}

		//$mainCat->main_category=$request->menu;
		$cat->logo=$logoName;
		$cat->is_main = 0;
		if($cat->main_category == 0){
			$cat->is_main = 1;
		}
     	 
     	$cat->save();
    	return redirect('/backend/serviceplacecategory/index');

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getDestroy($id)
	{
		$unit = ServiceMainCategory::find($id);
		$unit->delete();
		return redirect('/backend/serviceplacecategory/index');

	}

}
