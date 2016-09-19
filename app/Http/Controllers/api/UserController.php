<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use Faker\Provider\DateTime;
use App\Models\UserToken;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Intervention\Image\ImageManagerStatic as Image;
use Mail;
use DB;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App\Models\User;
use \App\Models\Directory;

use \App\Models\UserConfig;
use \Illuminate\Database\QueryException;
use App\Models\Neighbarhood;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('jwt.auth', ['except' => ['postLogin', 'postRegister', 'postLoginfb', 'getForgetpassword', 'postForgetpassword','postVerifymail']]);
    }

    public function postLogin(Request $request) {
        $output=array();
        $output["token"]='';
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;

        $rules = [
            'email' => 'required|email|exists:user,email',
            'password' => 'required|min:6',
            'device_type'=> 'required',
            'device_token'=> 'required'
        ];

        $input = Input::only(
            'email',
            'password',
            'device_type',
            'device_token'

        );
        $user_id=0;


        $validator = Validator::make($input, $rules);
        if ($validator->fails()){
            $output["result"]["message"]=$validator->errors();

            if ($output["result"]["message"]->has('email')||$output["result"]["message"]->has('password')){
                $output["result"]["errorcode"]=1;
            }


        }else{
            $user_test=User::where("email",$request['email'])->first();
            if ($user_test->is_active==1){
                $credentials = $request->only('email', 'password');
                try {
                    //reg attempt to verify the credentials and create a token for the user
                    if (!$token = JWTAuth::attempt($credentials)) {
                        $output["result"]["success"]=false;
                        $output["result"]["message"]="invalid_credentials ";
                        $output["result"]["errorcode"]=2;
                        return $output;
                    }

                } catch (JWTException $e) {
                    // something went wrong whilst attempting to encode the token
                    $output["result"]["success"]=false;
                    $output["result"]["message"]="could_not_create_token ";
                    $output["result"]["errorcode"]=10;
                    return $output;
                }
                $user_id=User::where('email',$request['email'])->first()->user_id;
                $userConfigtest=UserConfig::where(['device_token'=>$request['device_token'],"user_id"=>$user_id])->first();
                if ($userConfigtest){
                    $userConfigtest->update(['is_logout'=>0]);


                }else{
                    $userConfigAnother=UserConfig::where('user_id',$user_id)->first();
                    unset($request['email'],$request['password']);
                    $userConfig=new UserConfig($request->all());
                    $userConfig->user_id=$user_id;
                    $userConfig->language=1;
                    $userConfig->notification=1;
                    $userConfig->is_logout=0;
                    $userConfig->neighbarhood_id=$userConfigAnother->neighbarhood_id;

                    $userConfig->save();



                }


                $output["result"]["success"]=true;
                $output["result"]["message"]="user logged successfully ";
                $output["result"]["errorcode"]=0;
                $output["token"]=$token;
            }else{
                $output["result"]["success"]=false;
                $output["result"]["message"]="user not active now u should activate your account first ";
                $output["result"]["errorcode"]=0;

            }


        }


        // all good so return the token
//        return response()->json(compact('token'));

        return $output;
    }

    public function postVerifymail(Request $request)
    {
        $output=array();
        $output["token"]='';
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;

        $rules = [
            'email' => 'required|email|exists:user,email',
            'verify_email'=> 'required',
            'password' => 'required|min:6',
//            'device_type'=> 'required',
//            'device_token'=> 'required'

        ];

        $input = Input::only(
            'email',
            'verify_email',
            'password',
            'device_type',
            'device_token'


        );



        $validator = Validator::make($input, $rules);
        if ($validator->fails()){

            $output["result"]["message"]=$validator->errors();

            if ($output["result"]["message"]->has('email')||$output["result"]["message"]->has('verify_email')){
                $output["result"]["errorcode"]=1;
            }


        }else{


                ////
                $credentials = $request->only('email', 'password');
                try {
                    // attempt to verify the credentials and create a token for the user
                    if (!$token = JWTAuth::attempt($credentials)) {
                        $output["result"]["success"]=false;
                        $output["result"]["message"]="invalid_credentials ";
                        $output["result"]["errorcode"]=2;
                        return $output;
                    }
                    $user_test=User::where("email",$request['email'])->first();
                    $user_id=$user_test->user_id;
                } catch (JWTException $e) {
                    // something went wrong whilst attempting to encode the token
                    $output["result"]["success"]=false;
                    $output["result"]["message"]="could_not_create_token ";
                    $output["result"]["errorcode"]=10;
                    return $output;
                }
            if($user_test->is_active==0){
                DB::table('user')
                    ->where('user_id', $user_test->user_id)
                    ->update(['is_active' => 1]);

                    $userConfigtest=UserConfig::where('device_token',$request['device_token'])->first();

                    $userConfigAnother=UserConfig::where('user_id',$user_id)->first();
                    unset($request['email'],$request['password']);
                    $userConfig=new UserConfig($request->all());
                    $userConfig->user_id=$user_id;
                    $userConfig->language=1;
                    $userConfig->notification=1;
                    $userConfig->is_logout=0;
                    $userConfig->neighbarhood_id=1;

                    $userConfig->save();





                $output["token"]=$token;
                /////
                $output["result"]["success"]=true;
                $output["result"]["message"]="Congratulations, your account is now active  =D";
                $output["result"]["errorcode"]=0;

            }
            else{
                $output["result"]["success"]=false;
                $output["result"]["message"]="your account is already activated !";
                $output["result"]["errorcode"]=0;

            }

        }
        return $output;

    }

    public function postLoginfb(Request $request)
    {

        $output=array();
        $output["token"]='';
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;

        $rules = [
            'email' => 'required|email',
            'facebook_id' => 'required',
            'name' => 'required',
            'image'=> 'required',
            'device_type'=> 'required',
            'device_token'=> 'required',

        ];

        $input = Input::only(
            'email',
            'facebook_id',
            'name',
            'image',
            'device_type',
            'device_token'


        );



        $validator = Validator::make($input, $rules);
        if ($validator->fails()){

            $output["result"]["message"]=$validator->errors();

            if ($output["result"]["message"]->has('email')||$output["result"]["message"]->has('facebook_id')){
                $output["result"]["errorcode"]=1;
            }


        }else{

            $name = explode(' ', $request['name']);
            if (count($name) ==2){
                $first_name=$name[0];
                $last_name=$name[1];
            }else{
                $validator->errors()->add('name', 'name should be two parts first and last');
                $output["result"]["message"]=$validator->errors();
                $output["result"]["errorcode"]=1;

                return $output;
            }

            ///
            $device_token=$request['device_token'];
            $device_type=$request['device_type'];
            //
            unset($request['name'],$request['device_type'],$request['device_token']);
            $request['first_name']=$first_name;
            $request['last_name']=$last_name;
            $user=User::where('facebook_id',$request['facebook_id'])->first();
            if ($user){
                $token = JWTAuth::fromUser($user);
                $output["result"]["success"]=true;
                $output["result"]["message"]="user logged in successfully ";
                $output["result"]["errorcode"]=0;
                $output["token"]=$token;

                $userConfigtest=UserConfig::where('device_token',$device_token)->first();
                if ($userConfigtest){
                    $userConfigtest->update(['is_logout'=>0]);


                }else{
                    $userConfigAnother=UserConfig::where('user_id',$user->user_id)->first();
                    unset($request['email'],$request['password']);
                    $request['device_token']=$device_token;
                    $request['device_type']=$device_type;
                    $userConfig=new UserConfig($request->all());
                    $userConfig->user_id=$user->user_id;
                    $userConfig->language=1;
                    $userConfig->notification=1;
                    $userConfig->is_logout=0;
                    $userConfig->neighbarhood_id=$userConfigAnother->neighbarhood_id;

                    $userConfig->save();



                }


            }else{
                $user= User::create($request->all());
                $token = JWTAuth::fromUser($user);
                $output["result"]["success"]=true;
                $output["result"]["message"]="user registered successfully ";
                $output["result"]["errorcode"]=0;
                $output["token"]=$token;


                unset($request['email'],$request['password']);
                $request['device_token']=$device_token;
                $request['device_type']=$device_type;

                $userConfig=new UserConfig($request->all());
                $userConfig->user_id=$user->user_id;
                $userConfig->language=1;
                $userConfig->notification=1;
                $userConfig->is_logout=0;
                $userConfig->neighbarhood_id='1';

                $userConfig->save();









            }





        }


        return $output;
    }

    public function getLogout(Request $request) {
        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;



        $this->validate($request, [
            'device_token' => 'required'
        ]);

        $out =JWTAuth::invalidate($request->input('token'));
        if ($out){
            $output["result"]["success"]=true;
            $output["result"]["message"]="logged out  successfully";
            $output["result"]["errorcode"]=0;
            $userConfigtest=UserConfig::where('device_token',$request['device_token'])->first();
            if ($userConfigtest){
                $userConfigtest->update(['is_logout'=>1,'notification'=>0]);


            }
        }
        return $output;
    }

    public function postRegister(Request $request)
    {

        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;

        $rules = [
            'full_name' => 'required|min:7',
            'image'=>'required|image',
            'email' => 'required|email|unique:user',
            'device_type'=> 'required',
            'device_token'=> 'required',
            'password' => 'required|confirmed|min:6'



        ];

        $input = Input::only(
            'full_name',
            'email',
            'image',
            'device_type',
            'device_token',
            'password',
            'password_confirmation'

        );

        $validator = Validator::make($input, $rules);

        if($validator->fails())
        {
            $output["result"]["message"]=$validator->errors();

            if ($output["result"]["message"]->has('email')){
                $output["result"]["errorcode"]=3;
            } elseif ($output["result"]["message"]->has('password')){
                $output["result"]["errorcode"]=2;
            }elseif ($output["result"]["message"]->has('first_name')&&$output["result"]["message"]->has('last_name')){
                $output["result"]["errorcode"]=1;
            }


        }else{
            $userConfigtest=UserConfig::where('device_token',$request['device_token'])->get();
            if (count($userConfigtest)>3){
                $output["result"]["message"]="You have only 3 accounts with this device token ";
                $output["result"]["errorcode"]=10;


            }else{

                $verify_email = bin2hex(random_bytes(5));

                $name = explode(' ', $request['full_name']);
                if (count($name) ==2){
                    $first_name=$name[0];
                    $last_name=$name[1];
                }else{
                    $validator->errors()->add('full_name', 'name should be two parts first and last');
                    $output["result"]["message"]=$validator->errors();
                    $output["result"]["errorcode"]=1;

                    return $output;
                }
                $request['first_name'] = $first_name;
                $request['last_name'] = $last_name;
                $newuser= $request->all();
                unset($newuser['password_confirmation'],$newuser['full_name']);
                $newuser['image'] =$request->file('image');
                $newuser['verify_email'] =$verify_email;

                $discussion_original=Directory::where('key','=','users_original')->first();
                $discussion_thumb=Directory::where('key','=','users_thumb')->first();
                $destinationPath =  public_path().$discussion_original->path;
                $destinationPathurl =  url(). $discussion_original->path;
                $destinationPathThumb =public_path().$discussion_thumb->path;

                $extension =  $newuser['image']->getClientOriginalExtension(); // getting image extension
                $image_name=explode('.',  $newuser['image']->getClientOriginalName());
                $fileName = $newuser['email'] . '.' . $extension; // renameing image

                $newuser['image']->move($destinationPath, $fileName); // uploading file to given path
                $all=$destinationPathurl.$fileName;
                $thum=$destinationPathThumb.$fileName;
                //Image::make($all)->resize(200, 200)->save($thum);

                $newuser['image']=$fileName;
                $newuser['password'] = bcrypt($request->input('password'));
//                $newuser['is_active']=1;
                $user= User::create($newuser);

//                Mail::send('emails.welcome', ['user' => $user], function ($m) use ($user) {
//                    $m->from('jeeran@jeeran.gn4me.com', 'Jeeran Application');
//                    $m->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Account change password');
//                });

              Mail::queue('emails.welcome', ['user' => $user], function ($m) use ($user) {
                    $m->from('jeeran@jeeran.gn4me.com', 'Jeeran Application');

                    $m->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Welcome to jeeran app');
                });

                $userConfig=new UserConfig(['device_type'=>$request['device_type'],'device_token'=>$request['device_token']]);
                $userConfig->user_id=$user->user_id;
                $userConfig->language=1;
                $userConfig->notification=1;
                $userConfig->is_logout=0;
                $userConfig->neighbarhood_id='1';
                ///
                $userConfig->save();
                $output["result"]["success"]=true;
                $output["result"]["message"]="user registered successfully   you have to verify the account by the code sent to your mail";
                $output["result"]["errorcode"]=0;
            }

        }

        return $output;



    }

    public function getResetpassword(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;

        $user = JWTAuth::parseToken()->authenticate();
        $rules = [
            'email' => 'required|email'
        ];

        $input = Input::only(
                        'email'
        );



        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $output["result"]["message"] = $validator->errors();

            if ($output["result"]["message"]->has('email')) {
                $output["result"]["errorcode"] = 10;
            }
        } else {

            $reset_password_code= bin2hex(random_bytes(5));
            $user_token=UserToken::create(['reset_password_code'=>$reset_password_code,'created_at'=>Carbon::now(),'expire_at'=>Carbon::now()->addHours(3),'user_id'=>$user->user_id]);/////////

            Mail::queue('emails.ResetPassword', ['user_token' => $user_token], function ($m) use ($user) {
                $m->from('jeeran@jeeran.gn4me.com', 'Jeeran Application');

                    $m->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Account change password');
            });
            $output["result"]["success"] = true;
            $output["result"]["message"] = "email has been sent successfully mail ";
            $output["result"]["errorcode"] = 0;
        }
        return $output;
    }

    public function postResetpassword(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;


        $rules = [
            'email' => 'required|email',
            'code' => 'required ',
            'password' => 'required|confirmed|min:8'
        ];

        $input = Input::only(
                        'email', 'code', 'password', 'password_confirmation'
        );



        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $output["result"]["message"] = $validator->errors();

            if ($output["result"]["message"]->has('email') || $output["result"]["message"]->has('password')) {
                $output["result"]["errorcode"] = 10;
            }
        } else {

            if ($user->email === $request['email']) {
                $user_token_check=UserToken::where('reset_password_code',$request['code'])->where('user_id',$user->user_id)->first();


                if ($user_token_check) {
                    if ( Carbon::createFromTimestampUTC(strtotime($user_token_check->expire_at))->lte( Carbon::now()))
                    {

                        $output["result"]["message"] = "password reset code is expired please try to do reset password process again to get new code  ";
                        $output["result"]["errorcode"] = 10;

                    }else{

                        $user->password = bcrypt($request['password']);
                        $user->save();
                        //output succsses
                        $output["result"]["success"] = true;
                        $output["result"]["message"] = "user changed password successfully ";
                        $output["result"]["errorcode"] = 0;
                    }

                } else {
                    $validator->errors()->add('code', 'code mismatch!');

                    $output["result"]["message"] = $validator->errors();
                    $output["result"]["errorcode"] = 10;
                }
            } else {
                $validator->errors()->add('email', 'please insert your email address');

                $output["result"]["message"] = $validator->errors();
                $output["result"]["errorcode"] = 10;
            }
        }




        return $output;
    }

    public function getForgetpassword(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;

        $rules = [
            'email' => 'required|email|exists:user,email'
        ];

        $input = Input::only(
                        'email'
        );



        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            $output["result"]["message"] = $validator->errors();

            if ($output["result"]["message"]->has('email')) {
                $output["result"]["errorcode"] = 10;
            }
        } else {
            $user = User::where('email', $request['email'])->first();
            if ($user->last_forget_password){
                $last_forgetpassword=Carbon::createFromTimestampUTC(strtotime($user->last_forget_password));
                if ( $last_forgetpassword->addHours(3)->gt( Carbon::now())){

                    $output["result"]["message"] = "email with the code already has been sent to your mail u can send another mail".
                                                    " after 3 hours form the last one ";
                    $output["result"]["errorcode"] = 10;
                    return $output;
                }
            }
            $reset_password_code= bin2hex(random_bytes(5));
            $user_token=UserToken::create(['reset_password_code'=>$reset_password_code,'created_at'=>Carbon::now(),'expire_at'=>Carbon::now()->addHours(3),'user_id'=>$user->user_id]);/////////

            Mail::queue('emails.ResetPassword', ['user_token' => $user_token], function ($m) use ($user) {
                $m->from('jeeran@jeeran.gn4me.com', 'Jeeran Application');

                $m->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Account forget password');
            });
            DB::table('user')
                ->where('user_id', $user->user_id)
                ->update(['last_forget_password'=>Carbon::now()]);

            $output["result"]["success"] = true;
            $output["result"]["message"] = "email has been sent successfully  to your mail";
            $output["result"]["errorcode"] = 0;


        }
        return $output;
    }

    public function postForgetpassword(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;


        $rules = [
            'email' => 'required|email',
            'code' => 'required ',
            'password' => 'required|confirmed|min:8'
        ];

        $input = Input::only(
                        'email', 'code', 'password', 'password_confirmation'
        );


        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $output["result"]["message"] = $validator->errors();

            if ($output["result"]["message"]->has('email') || $output["result"]["message"]->has('password')) {
                $output["result"]["errorcode"] = 10;
            }
        } else {
            $user = User::where('email', $request['email'])->first();
            if ($user) {

                $user_token_check=UserToken::where('reset_password_code',$request['code'])->where('user_id',$user->user_id)->first();
                if ($user_token_check) {
                    if ( Carbon::createFromTimestampUTC(strtotime($user_token_check->expire_at))->lte( Carbon::now()))
                    {
                        $output["result"]["message"] = "password forget code is expired please try to do forget password process again to get new code  ";
                        $output["result"]["errorcode"] = 10;

                    }else{

                        $user->password = bcrypt($request['password']);
                        $user->save();
                        $output["result"]["success"] = true;
                        $output["result"]["message"] = "user changed password successfully ";
                        $output["result"]["errorcode"] = 0;
                    }
                } else {
                    $validator->errors()->add('code', 'code mismatch');

                    $output["result"]["message"] = $validator->errors();
                    $output["result"]["errorcode"] = 10;
                }
            }
        }

        return $output;
    }

    public function getMyprofile(Request $request) {
        $output = array();
        $output["result"] = array();
        $output["result"]["success"] = false;
        $output["result"]["message"] = "Unknown error";
        $output["result"]["errorcode"] = 10;
        $user = JWTAuth::parseToken()->authenticate();
        $rules = [
            'device_token'=> 'required|exists:user_config,device_token'

        ];

        $input = Input::only(

            'device_token'

        );

        $validator = Validator::make($input, $rules);

        if($validator->fails())
        {
            $output["result"]["message"]=$validator->errors();

            return $output;


        }
        if ($user->user_id && $request->has("device_token")) {
            $user_config_test= UserConfig::where("user_id",$user->user_id)->where("device_token",$request['device_token'])->first();
            if (!$user_config_test){
                $validator->errors()->add('device_token', 'wrong device token');
                $output["result"]["message"]=$validator->errors();
                $output["result"]["errorcode"]=1;

                return $output;
            }

            $user_configs = User::where(function($query) use ($user) {
                        $query->where("user_id", $user->user_id);
                    })
                    ->get(["user_id", "first_name as fName", "last_name as lName", "email as mail", "image", "date_of_birth as dateOfBirth", "facebook_id as fb_id", "mobile_number"]);
            $user_configs->load(array("userconfigs" => function($q) {
                    $q->addSelect(array("user_id", "neighbarhood_id", "language as lang", "notification", "device_type", "device_token"))->where('device_token',$_REQUEST['device_token']);
                }))[0]->userconfigs[0]->load(array("neighbarhood" => function($q) {
                            $q->addSelect(array("neighbarhood_id", "title_ar", "title_en"));
                        }));
            $users_original=Directory::where('key','=','users_original')->first();

            $usersPath =  url().$users_original->path;

                            foreach ($user_configs as $userconf) {
                                $result = array();
                                $result["user_id"] = $userconf->user_id;
                                $result["fName"] = $userconf->fName;
                                $result["lName"] = $userconf->lName;
                                $result["mail"] = $userconf->mail;
                                if (str_contains($userconf->image, 'fbcdn.net')){
                                    $result["image"] = $userconf->image;
                                }else{

                                    $usersPath =  url().$users_original->path;
                                    $result["image"] = $usersPath.$userconf->image;
                                }
                                $result["dateOfBirth"] = $userconf->dateOfBirth;
                                $result["fb_id"] = $userconf->fb_id;
                                $result["mobile_number"] = $userconf->mobile_number;
                                $result["neighborhood_id"] = $userconf->userconfigs[0]->neighbarhood_id;
                                $result["neighborhood_ar"] = $userconf->userconfigs[0]->neighbarhood->title_ar;
                                $result["neighborhood_en"] = $userconf->userconfigs[0]->neighbarhood->title_en;
                                $result["lang"] = $userconf->userconfigs[0]->lang;
                                $result["notification"] = $userconf->userconfigs[0]->notification;
                                $result["device_token"] = $userconf->userconfigs[0]->device_token;
                                $result["device_type"] = $userconf->userconfigs[0]->device_type;
                            }

                            if (count($result) > 0) {
                                $output["result"]["success"] = true;
                                $output["result"]["message"] = "Success";
                                $output["result"]["errorcode"] = 0;
                                $output["response"] = $result;
                            } else {
                                $output["result"]["message"] = "No result found";
                                $output["result"]["errorcode"] = 2;
                            }
                        } else {
                            $output["result"]["success"] = false;
                            $output["result"]["message"] = "Missing Inputs";
                            $output["result"]["errorcode"] = 1;
                        }

                        return $output;
                    }

    public function postProfileupdate(Request $request)

    {
        $current_user = JWTAuth::parseToken()->authenticate();


        $output=array();
        $output["result"]=array();
        $output["result"]["success"]=false;
        $output["result"]["message"]="Unknown error";
        $output["result"]["errorcode"]=10;

        $rules = [
            'first_name' => 'min:3',
            'last_name' => 'min:3',
            'image'=>'image',
            'date_of_birth'=> 'date',
            'Mobile'=> 'integer'



        ];

        $input = Input::only(
            'first_name',
            'last_name',
            'image',
            'date_of_birth',
            'Mobile'
        //mobile_number

        );

        $mobile_num=$request['Mobile'];
        unset($request['Mobile']);
        $request['mobile_number']=$mobile_num;

        $validator = Validator::make($input, $rules);

        if($validator->fails())
        {
            $output["result"]["message"]=$validator->errors();


        }else{
            if ($request){
                $newuser=$request->all();
                if ($request->has('image')){
                    $newuser['image'] =$request->file('image');

                    $destinationPath = base_path() . '/public/uploads/usersimages/original/';
                    $destinationPathThumb = base_path() . '/public/uploads/usersimages/thumbnail/';
                    $extension = $newuser['image']->getClientOriginalExtension(); // getting image extension
                    $image_name = explode('.', $newuser['image']->getClientOriginalName());
                    $fileName = $image_name[0] . '_' . $newuser['email'] . '.' . $extension; // renameing image
                    $newuser['image']->move($destinationPath, $fileName); // uploading file to given path
                    $all=$destinationPath.$fileName;
                    $thum=$destinationPathThumb.$fileName;
                    $img = Image::make($all,array(
                        'width' => 40,
                        'height' => 40,
                        'grayscale' => true
                    ))->save($thum);

                }
                return $request->all();
                DB::table('user')
                    ->where('user_id', $current_user->user_id)
                    ->update($newuser);



                $output["result"]["success"]=true;
                $output["result"]["message"]="user profile updated successfully ";
                $output["result"]["errorcode"]=0;

            }else{
                $output["result"]["message"]=" Request has no date to  update user profile! ";
            }


        }

        return $output;



    }

    public function getLoadsetting(Request $request) {
                        $output = array();
                        $output["result"] = array();
                        $output["result"]["success"] = false;
                        $output["result"]["message"] = "Unknown error";
                        $output["result"]["errorcode"] = 10;
                        $user = JWTAuth::parseToken()->authenticate();
                        if ($user->user_id && $request->has("device_token")) {
                            $user_configs = UserConfig::where(["user_id" => $user->user_id, "device_token" => $request->get("device_token")])
                                    ->get(["user_id", "language as lang", "notification", "device_type", "device_token"]);

                            if (count($user_configs) > 0) {
                                $output["result"]["success"] = true;
                                $output["result"]["message"] = "Success";
                                $output["result"]["errorcode"] = 0;
                                $output["response"] = $user_configs[0];
                            } else {
                                $output["result"]["message"] = "Device not found";
                                $output["result"]["errorcode"] = 2;
                            }
                        } else {
                            $output["result"]["success"] = false;
                            $output["result"]["message"] = "Missing Inputs";
                            $output["result"]["errorcode"] = 1;
                        }

                        return $output;
                    }

    public function getGetneighborhood(Request $request) {
                        $output = array();
                        $output["result"] = array();
                        $output["result"]["success"] = false;
                        $output["result"]["message"] = "Unknown error";
                        $output["result"]["errorcode"] = 10;
                        $user = JWTAuth::parseToken()->authenticate();
                        if ($user->user_id && $request->has("device_token")) {
                            $user_configs = UserConfig::where(["user_id" => $user->user_id, "device_token" => $request->get("device_token")])->get();

                            if (count($user_configs) <= 0) {
                                $output["result"]["success"] = false;
                                $output["result"]["message"] = "Device not found";
                                $output["result"]["errorcode"] = 3;
                                return $output;
                            }

                            $user_configs->load(array("neighbarhood" => function($q) {
                                    $q->addSelect(array("neighbarhood_id", "title_ar", "title_en"));
                                }));
                                    foreach ($user_configs as $userconf) {
                                        $result = array();
                                        $result["neighborhood_id"] = $userconf->neighbarhood_id;
                                        $result["neighborhood_ar"] = $userconf->neighbarhood->title_ar;
                                        $result["neighborhood_en"] = $userconf->neighbarhood->title_en;
                                        $result["device_token"] = $userconf->device_token;
                                        $result["device_type"] = $userconf->device_type;
                                    }
                                    if (count($result) > 0) {
                                        $output["result"]["success"] = true;
                                        $output["result"]["message"] = "Success";
                                        $output["result"]["errorcode"] = 0;
                                        $output["response"] = $result;
                                    } else {
                                        $output["result"]["message"] = "No result found";
                                        $output["result"]["errorcode"] = 2;
                                    }
                                } else {
                                    $output["result"]["success"] = false;
                                    $output["result"]["message"] = "Missing Inputs";
                                    $output["result"]["errorcode"] = 1;
                                }

                                return $output;
                            }

    public function postUpdatesetting(Request $request) {
                                $output = array();
                                $output["result"] = array();
                                $output["result"]["success"] = false;
                                $output["result"]["message"] = "Unknown error";
                                $output["result"]["errorcode"] = 10;

                                $user = JWTAuth::parseToken()->authenticate();

                                $validator = Validator::make(["lang" => $request->lang, "notification" => $request->notification], ["lang" => "in:0,1", "notification"=>"in:0,1"]);
                                if ($validator->fails()) {
                                    $output["result"]["message"] = "Language/Notification Mismatch value";
                                    $output["result"]["errorcode"] = 3;
                                    return $output;
                                }
                                if ($user->user_id && $request->has("device_token") && (($request->has("lang") && in_array($request->get("lang"), [0, 1])) || $request->has("notification") && in_array($request->get("notification"), [0, 1]))) {
                                    $userconfigs = UserConfig::where(["user_id" => $user->user_id, "device_token" => $request->get("device_token")]);

                                    if (count($userconfigs->get()) <= 0) {
                                        $output["result"]["success"] = false;
                                        $output["result"]["message"] = "Device not found";
                                        $output["result"]["errorcode"] = 2;
                                        return $output;
                                    }

                                    try {
                                        if ($request->has("lang") && in_array($request->get("lang"), [0, 1])) {
                                            $userconfigs->update(["language" => $request->get("lang")]);
                                        }

                                        if ($request->has("notification") && in_array($request->get("notification"), [0, 1])) {
                                            $userconfigs->update(["notification" => $request->get("notification")]);
                                        }

                                        $output["result"]["success"] = true;
                                        $output["result"]["message"] = "Success";
                                        $output["result"]["errorcode"] = 0;
                                    } catch (QueryException $ex) {

                                        $output["result"]["success"] = false;
                                        $output["result"]["message"] = "Failed to update settings";
                                        $output["result"]["errorcode"] = 4;
                                        return $output;
                                    }
                                } else {
                                    $output["result"]["success"] = false;
                                    $output["result"]["message"] = "Missing Inputs";
                                    $output["result"]["errorcode"] = 1;
                                }

                                return $output;
                            }

    public function getUpdateneighborhood(Request $request) {
                                $output = array();
                                $output["result"] = array();
                                $output["result"]["success"] = false;
                                $output["result"]["message"] = "Unknown error";
                                $output["result"]["errorcode"] = 10;

                                $user = JWTAuth::parseToken()->authenticate();
                                if ($user->user_id && $request->has("device_token") && $request->has("neighborhood_id")) {
                                    $userconfigs = UserConfig::where(["user_id" => $user->user_id, "device_token" => $request->get("device_token")]);

                                    if (count($userconfigs->get()) <= 0) {
                                        $output["result"]["success"] = false;
                                        $output["result"]["message"] = "Device not found";
                                        $output["result"]["errorcode"] = 2;
                                        return $output;
                                    }
                                    $checkNeighbarhood = Neighbarhood::where('neighbarhood_id' , $request->get("neighborhood_id"))->get();// in_array($request->get("neighborhood_id"), ));

                                    try {
                                        if (count($checkNeighbarhood) > 0) {
                                            $userconfigs->update(["neighbarhood_id" => $request->get("neighborhood_id")]);

                                            $output["result"]["success"] = true;
                                            $output["result"]["message"] = "Success";
                                            $output["result"]["errorcode"] = 0;
                                        } else {
                                            $output["result"]["success"] = false;
                                            $output["result"]["message"] = "Neighborhood not found";
                                            $output["result"]["errorcode"] = 4;
                                        }
                                    } catch (QueryException $ex) {

                                        $output["result"]["success"] = false;
                                        $output["result"]["message"] = "Failed to update neighborhood";
                                        $output["result"]["errorcode"] = 3;
                                        return $output;
                                    }
                                } else {
                                    $output["result"]["success"] = false;
                                    $output["result"]["message"] = "Missing Inputs";
                                    $output["result"]["errorcode"] = 1;
                                }
                                return $output;
                            }

    public function getUpdatepassword() {
                                $output = array();
                                $output["result"] = array();
                                $output["result"]["success"] = false;
                                $output["result"]["message"] = "Unknown error";
                                $output["result"]["errorcode"] = 10;

                                $user = JWTAuth::parseToken()->authenticate();
                                if ($user->user_id && $request->has("oldpassword") && $request->has("newpassword")) {
                                    $user = User::where(["user_id" => $user->user_id]);
                                    if ($user->user_password == bcrypt($request->has("oldpassword"))) {
                                        $user->update(["user_password" => $request->get("newpassword")]);

                                        $output["result"]["success"] = true;
                                        $output["result"]["message"] = "Success";
                                        $output["result"]["errorcode"] = 0;
                                    } else {
                                        $output["result"]["success"] = false;
                                        $output["result"]["message"] = "Mismatch old password";
                                        $output["result"]["errorcode"] = 2;
                                    }
                                } else {
                                    $output["result"]["success"] = false;
                                    $output["result"]["message"] = "Missing Inputs";
                                    $output["result"]["errorcode"] = 1;
                                }
                                return $output;
                            }

                        }
