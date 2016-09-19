<?php namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

use Illuminate\Http\Request;

class AdminNotitficationController extends Controller {


	public function __construct()
	{
		$this->middleware('chkAdminAuth');

	}

	public function getNotify()
	{
		$notification =array();

		//for users
		$noti= DB::table('report')
			->select(array('report.*','user.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 3,"is_seen"=>0])
			->join('user', 'report.reported_id', '=', 'user.user_id')
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();
		if (count($noti)>0){
			$notification['users']=$noti;
		}


		//for service_place
		$noti= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 1,"is_seen"=>0])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();
		if (count($noti)>0){
			$notification['service_place']=$noti;
		}


		//for service_place_review
		$noti= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=>2,"is_seen"=>0])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();

		if (count($noti)>0){
			$notification['service_place_review']=$noti;
		}


		//for discussion
		$noti= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 4,"is_seen"=>0])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();
		if (count($noti)>0){
			$notification['discussion']=$noti;
		}


		//for discussion_comment
		$noti= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 5,"is_seen"=>0])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();
		if (count($noti)>0){
			$notification['discussion_comment']=$noti;
		}


		//for real_estate_ad
		$noti= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 6,"is_seen"=>0])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();
		if (count($noti)>0){
			$notification['real_estate_ad']=$noti;
		}


		//for real_estate_ad_comment
		$noti= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 7,"is_seen"=>0])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();
		if (count($noti)>0){
			$notification['real_estate_ad_comment']=$noti;
		}






		return $notification;

	}

	public function postSeen()
	{
		$arr=array();

		$done=DB::table('report')
			->update(['is_seen' => 1]);
		$arr["done"]=$done;
		return $arr;

	}

	public function getListall()
	{

		DB::table('report')
			->update(['is_seen' => 1]);

		$notification =array();

		//for users
		$users= DB::table('report')
			->select(array('report.*','user.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 3])
			->join('user', 'report.reported_id', '=', 'user.user_id')
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();


		//for service_place
		$service_place= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 1])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();



		//for4 service_place_review
		$service_place_review= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=>2])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();




		//for discussion
		$discussion= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 4])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();



		//for discussion_comment
		$discussion_comment= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 5])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();


		//for real_estate_ad
		$real_estate_ad= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 6])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();



		//for real_estate_ad_comment
		$real_estate_ad_comment= DB::table('report')
			->select(array('report.*', DB::raw('COUNT( reported_id ) AS report_count')))
			->where(['reported_type_id'=> 7])
			->groupBy('reported_id')
			->having('report_count', '>',2)
			->get();



		return view('backend.admin.viewNotification', compact('users','service_place_review','service_place','real_estate_ad_comment','real_estate_ad','discussion','discussion_comment'));


	}



}
