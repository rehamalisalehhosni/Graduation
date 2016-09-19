<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Database\QueryException;
use DB;
use Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\PaginationServiceProvider;

use Illuminate\Http\Request;

class AdminController extends Controller {


	public function __construct()
	{
		$this->middleware('chkAdminAuth',['except'=>['getLogin','postLogin','getForgetpassword','postForgetpassword','getResetpassword','postResetpassword']]);
		$this->middleware('chkSuperAdmin',['only'=>['getAddadmin','postAddadmin','getAdminslist','getEditadmin','postEditadmin']]);


	}

	public function getSearch(Request $request)
	{
		$keyword=$request['keyword'];
		if ($keyword){
			$admins=DB::table('admin')
				->where('admin_name','like', '%'.$keyword.'%')
				->orWhere('user_name','like', '%'.$keyword.'%')
				->orWhere('email','like', '%'.$keyword.'%')
				->Paginate(10);;

			return view('backend.admin.searchadmin', compact('admins','keyword'));
		}

		return redirect('/backend/admin/adminslist');



	}


	public function getAdminslist()
	{
		$admins = DB::table('admin')->Paginate(10);

		return view('backend.admin.listadmins', compact('admins'));

	}

	public function getEditadmin(Request $request)
	{
		$admin_id=$request['admin_id'];

		$admin=Admin::where('admin_id',$admin_id)->first();
		if (!$admin){
			return redirect('/backend/admin/adminslist');
		}

		if ($admin->account_type_id==1){
			return redirect('/backend/admin/adminslist');
		}


		return view('backend.admin.editadmin', compact('admin'));

	}

	public function postUpdateadmin(Request $request)
	{
		$admin_id=$request['admin_id'];

		$admin=Admin::where('admin_id',$admin_id)->first();

		if ($admin->account_type_id==1){
			return redirect('/backend/admin/adminslist');
		}


		$rules = [
			'email' => 'email',
			'user_name' => 'min:5',
			'admin_name'=>'min:6',
			'password' => 'confirmed|min:8'
		];

		$input = Input::only(
			'email', 'user_name','admin_name', 'password', 'password_confirmation'
		);



		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {

			return redirect("/backend/admin/editadmin?admin_id=$admin_id")
				->withErrors($validator)
				->withInput();

		} else {


			if ($request['email']){


				$admin = DB::table('admin')->where('email',$request['email'])->where('admin_id','<>',$admin_id)->first();
				if ($admin){

					$validator->errors()->add('email', "The email address already exists ");
					return redirect('/backend/admin/editadmin')
						->withErrors($validator)
						->withInput();
				}else{
					if ($request['password']){

						$request['user_password']= Hash::make($request['password']);

					}

					unset($request['admin_id'],$request['_token'],$request['password_confirmation'],$request['password']);
//						return $request->all();
						DB::table('admin')
							->where('admin_id', $admin_id)
							->update($request->all());
						return redirect('/backend/admin/adminslist');

				}


			}

		}


	}

	public function postDeleteadmin(Request $request)
	{
		$data=array();
		if ($request['admin_id']){

			$admin=Admin::where('admin_id',$request['admin_id'])->first();

			if ($admin->account_type_id==1){
				return redirect('/backend/admin/adminslist');
			}

			$admin=DB::table('admin')->where('admin_id', $request['admin_id'])->delete();
			if($admin){
				$data['deleted']=1;

			}else{
				$data['deleted']=0;
			}

			return  Response::json($data);
		}

		return redirect('/backend/admin/adminslist');


	}
	public function postBanchange(Request $request)
	{
		$data=array();
		$admin=Admin::where('admin_id',$request['admin_id'])->first();

		if ($admin->account_type_id==1){
			$data['is_active']=1;
			return  Response::json($data);
		}

		if($admin->is_active){
			$data['is_active']=0;

		}else{
			$data['is_active']=1;
		}

		DB::table('admin')
			->where('admin_id', $request['admin_id'])
			->update(['is_active' => $data['is_active']]);
		return  Response::json($data);




	}


	public function getAddadmin()
	{
		return view('backend.admin.addadmin');

	}


	public function postAddadmin(Request $request)
	{

		$rules = [
			'email' => 'required|email',
			'name' => 'required',
			'admin_name'=>'required',
			'password' => 'required|confirmed|min:8'
		];

		$input = Input::only(
			'email', 'name','admin_name', 'password', 'password_confirmation'
		);



		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {

			return redirect('/backend/admin/addadmin')
				->withErrors($validator)
				->withInput();

		} else {
			$admin = DB::table('admin')->where('email',$request['email'])->first();
			if ($admin){

				$validator->errors()->add('email', "The email address already exists ");
				return redirect('/backend/admin/addadmin')
					->withErrors($validator)
					->withInput();
			}else{
				if (Session::get('user')[0]['admin_type']==1){
					$request['password']= Hash::make($request['password']);
					$request['user_name']=$request['name'];

					$admin_create=Admin::create(['email'=>$request['email'],'user_name'=>$request['name'],'user_password'=>$request['password'],'admin_name'=>$request['admin_name'],'is_active'=>1	,'account_type_id'=>2]);
					return redirect('/backend/admin/adminslist');
				}
			}
		}


	}



	public function getLogin()
	{
		if (Session::has('user')){
			return redirect('/backend/realstate');
		}
		return view('backend.admin.login');

	}

	public function postLogin(Request $request)
	{
		$rules = [
			'user_name' => 'required|exists:admin,user_name',
			'password' => 'required|min:6',

		];

		$input = Input::only(
			'user_name', 'password'
		);

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {

			return redirect('backend/admin/login')
				->withErrors($validator)
				->withInput();

		} else {
//			$admin=DB::table('admin')
//				->where('user_name',$request['user_name'])
//				->where('user_password' , bcrypt($request->input('password')));
//			$admin=Admin::where('user_name',$request['user_name'])
//				        -> where('user_password',md5($request['password']))->first();
			$admin=Admin::where('user_name',$request['user_name'])
				->first();


			if($admin){
				if (Hash::check($request['password'], $admin->user_password))
				{
					$admin_type= AccountType::where('account_type_id',$admin->account_type_id)->first();
//					return $admin_type->key;
					if($admin->is_active || $admin_type->key==1){
						$data = Array ();//key=1 is root , key=2 is admin
						$data['user_name']=$request['user_name'];
						$data['admin_type'] = $admin_type->key;

						Session::push('user', $data);
						return redirect('/backend/statistic');
					}else{
						$validator->errors()->add('user_name', 'you are not active the super admin blocked you !');
						return redirect('backend/admin/login')
							->withErrors($validator)
							->withInput();
					}



				}else{
					$validator->errors()->add('user_name', 'user name or password is not valid  try valid one!');
					return redirect('backend/admin/login')
						->withErrors($validator)
						->withInput();
				}

			}else{
				$validator->errors()->add('user_name', 'user name or password is not valid  try valid one!');
				return redirect('backend/admin/login')
					->withErrors($validator)
					->withInput();
			}


		}

		}

	public function getForgetpassword()
	{
		if (Session::has('user')){
			return redirect('/backend/realstate');
		}
		return view('backend.admin.forgetpassword');

	}

	public function postForgetpassword(Request $request)
	{

		$rules = [
			'email' => 'required|exists:admin,email',

		];

		$input = Input::only(
			'email', 'password'
		);

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {

			return redirect('/backend/admin/forgetpassword')
				->withErrors($validator)
				->withInput();

		} else {
			$reset_password_code = bin2hex(random_bytes(5));
			$user=Admin::where('email',$request['email'])->update(['reset_password_code'=>$reset_password_code]);
//			$user=Admin::where('email',$request['email'])->
			$user_token = DB::table('admin')->select('admin_name', 'email','reset_password_code')->where('email',$request['email'])->first();
			$user=$user_token;

//			return dd($user_token	) ;
			  Mail::queue('emails.ResetPassword', ['user_token' => $user_token], function ($m) use ($user) {
				  $m->from('jeeran@jeeran.gn4me.com', 'Jeeran Application');
			   $m->to($user->email, $user->admin_name)->subject('Account change password');
			   });
			Session::flash('email', 'The restPassword code has been sent to your email check it out');

			return redirect('/backend/admin/resetpassword');

		}

	}

	public function getResetpassword()
	{
		if (Session::has('user')){
			return redirect('/backend/realstate');
		}
		return view('backend.admin.resetpassword');


	}

	public function postResetpassword(Request $request)
	{
		$rules = [
			'email' => 'required|email|exists:admin,email',
			'code' => 'required ',
			'password' => 'required|confirmed|min:8'
		];

		$input = Input::only(
			'email', 'code', 'password', 'password_confirmation'
		);



		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {

			return redirect('/backend/admin/resetpassword')
				->withErrors($validator)
				->withInput();

		} else {
			$admin = DB::table('admin')->where('email',$request['email'])->first();
				if ($admin->reset_password_code == $request['code']){
//					$admin->user_password = Hash::make($request['password']);
					DB::table('admin')
						->where('email', $request['email'])
						->where('reset_password_code',$request['code'])
						->update(['user_password' => Hash::make($request['password'])]);
					Session::flash('resetpassword','Password reset successfully');
					return redirect('backend/admin/login');
				}else{
					$validator->errors()->add('code', "The code isn't correct check your mail to get the vaild one ");
					return redirect('/backend/admin/resetpassword')
						->withErrors($validator)
						->withInput();
				}
		}

		}

	public function deleteLogout()
	{
		Session::forget('user');
		return redirect('/backend/admin/login');

	}


}
