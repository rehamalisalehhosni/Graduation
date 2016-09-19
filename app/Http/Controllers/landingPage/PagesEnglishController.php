<?php namespace App\Http\Controllers\landingPage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;
use Mail;

class PagesEnglishController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		//
		return view('landingPage.en.index');

	}
	public function getPolicy()
	{
		//
		return view('landingPage.en.policy');

	}
	public function getContact()
	{
		//
		return view('landingPage.en.contactUs');

	}
	public function postContact(Request $request)
	{
		$validator = Validator::make($request->all(), [
		            'name' => 'required',
		            'email' => 'required|email',
		            'subject' => 'required',
		            'message' => 'required',
		        ]);
						$output=array();
		        if ($validator->fails()) {
							$output['message']="<p class='alert alert-danger'>يرجي ادخال جميع الحقول </p>";
							$output['code']=0;
		        }else{
							Mail::send('emails.contactus',['request' => $request], function($message) use ($request)
							{
							    $message->from($request->email, $request->name);

							    $message->to('contactus@jeeran.gn4me.com');
							    $message->subject($request->subject);
							});


							$output['message']="<p class='alert alert-success'>تم إرسال الرسالة بنجاح، وسوف نرد عليك فى أقرب وقت. شكرا لإهتمامك </p>";
							$output['code']=1;
						}
						return response()->json($output);
	}

}
