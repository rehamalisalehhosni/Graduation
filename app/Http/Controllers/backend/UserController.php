<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Report;
use DB;

use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;



class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function __construct()
	{
		$this->middleware('chkAdminAuth',['except' => ['getEmailverification', 'getChangepassword', 'getForgetpassword']]);

//		$this->middleware('verifyCsrf',['except' => ['getListall']]);

	}

	public function getListall()
	{
//		$users=User::all()->Paginate(5);
		$users = DB::table('user')->Paginate(10);

		foreach ($users as $user){
			$user_reports=Report::where('reported_type_id',3)->where('reported_id',$user->user_id)->get();
			$user->user_reports=count($user_reports);
		}
//		return $users;
//		$users = DB::table('user')->get();

//		return dd($users->render());
		return view('backend.admin.listusers', compact('users'));

	}

	public function getListreports(Request $request)
	{
		$user_id=$request['user_id'];
		if ($user_id){
			$user=User::where('user_id',$user_id)->first();
			$user_reports= DB::table('report')
				->select( 'report.reporter_id','report.created_at','report_reason.title_en','report_reason.title_ar'
					,"user.first_name","user.last_name","user.image")
				->join('report_reason','report_reason.report_reason_id','=','report.report_reason_id')
				->join('user','user.user_id','=','report.reporter_id')
				->where('report.reported_type_id',3)
				->where('report.reported_id',$user_id)
				->get();
			
			return  view('backend.admin.listuserreports', compact('user','user_reports'));
		}
		
	}

	public function postBanchange(Request $request)
	{
		$data=array();
		$user=User::where('user_id',$request['user_id'])->first();
		if($user->is_active){
			$data['is_active']=0;

		}else{
			$data['is_active']=1;
		}

		DB::table('user')
			->where('user_id', $request['user_id'])
			->update(['is_active' => $data['is_active']]);
		return  Response::json($data);




	}

	public function getSearch(Request $request)
	{
		$keyword=$request['keyword'];
		if ($keyword){
			$users=DB::table('user')
				->where('first_name','like', '%'.$keyword.'%')
				->orWhere('last_name','like', '%'.$keyword.'%')
				->orWhere('email','like', '%'.$keyword.'%')
			->Paginate(10);;

			foreach ($users as $user){
				$user_reports=Report::where('reported_type_id',3)->where('reported_id',$user->user_id)->get();
				$user->user_reports=count($user_reports);
			}
			return view('backend.admin.searchusers', compact('users','keyword'));
		}

		return redirect('/backend/users/listall');
//		return  Response::json($users);


	}

        
        function getEmailverification(){
            return view('backend.admin.verify_email');
        }
        
        function getForgetpassword(){
            return view('backend.admin.forget_password');
        }
        
        function getChangepassword(){
            return view('backend.admin.change_password');
        }



}
