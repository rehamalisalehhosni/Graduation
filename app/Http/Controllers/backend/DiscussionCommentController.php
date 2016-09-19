<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\DiscussionComment;
use App\Models\Report;

class DiscussionCommentController extends Controller {

    public function __construct()
    {
        $this->middleware('chkAdminAuth');

    }

    public function getHide($id) {
        $comment = DiscussionComment::find($id);
        $comment->is_hide = 1;
        $comment->save();
        return redirect("/backend/discussion/show/$comment->discussion_id");
    }
    
    public function getDisplay($id) {
        $comment = DiscussionComment::find($id);
        $comment->is_hide = 0;
        $comment->save();
        return redirect("/backend/discussion/show/$comment->discussion_id");
    }
    
    public function getReports($id) {
        $reports = Report::Where('reported_type_id', '=', 5)->where('reported_id', '=', $id)->orderBy('report_id', 'desc')->paginate(5);
        return view("backend/discussion/reports", ['reports' => $reports]);
    }

}
