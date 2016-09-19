<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discussion;
use App\Models\Directory;
use App\Models\DiscussionComment;
use App\Models\Report;

class DiscussionController extends Controller {

    public function __construct()
    {
        $this->middleware('chkAdminAuth');

    }

    public function getIndex() {
        $discussions = Discussion::where('is_hide', '=', '0')->orderBy('discussion_id', 'desc')->paginate(5);
        $path = url('/backend/discussion/index');
        $discussions->setPath($path);

        return view("backend/discussion/index", ['discussions' => $discussions]);
    }

    public function getHidden() {
        $discussions = Discussion::where('is_hide', '=', '1')->orderBy('discussion_id', 'desc')->paginate(5);
        $path = url('/backend/discussion/hidden');
        $discussions->setPath($path);

        return view("backend/discussion/hidden", ['discussions' => $discussions]);
    }

    public function getShow($id) {
        $discussion = Discussion::find($id);
        $images_dir = Directory::where('key', '=', 'discussion_original')->first();
        $comments = DiscussionComment::where('discussion_id', '=', $id)->orderBy('discussion_comment_id', 'desc')->paginate(5);
        return view("backend/discussion/show", ['discussion' => $discussion, 'comments' => $comments, 'image_path' => $images_dir->path]);
    }

    public function getHide($id) {
        $realState = Discussion::find($id);
        $realState->is_hide = 1;
        $realState->save();
        return redirect('/backend/discussion/index');
    }

    public function getDisplay($id) {
        $realState = Discussion::find($id);
        $realState->is_hide = 0;
        $realState->save();
        return redirect('/backend/discussion/hidden');
    }

    public function getReports($id) {
        $reports = Report::Where('reported_type_id', '=', 4)->where('reported_id', '=', $id)->orderBy('report_id', 'desc')->paginate(5);
        return view("backend/discussion/reports", ['reports' => $reports]);
    }

    public function getSearch(Request $request) {
        $path = url('/backend/discussion/search');

        $keyword = $request->keyword;
        $discussions = Discussion::where('is_hide', '=', '0')
                        ->where('title', 'like', "%$keyword%")
                        ->orwhere('details', 'like', "%$keyword%")
                        ->orderBy('discussion_id', 'desc')->paginate(5);
        $discussions->setPath($path);

        return view("backend/discussion/search", ['discussions' => $discussions, 'keyword' => $keyword]);
    }

}
