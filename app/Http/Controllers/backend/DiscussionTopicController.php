<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiscussionTopic;

class DiscussionTopicController extends Controller {

    public function __construct()
    {
        $this->middleware('chkAdminAuth');

    }

    public function getIndex() {
        $topics = DiscussionTopic::all();
        return view("backend/discussion_topic/index", ['topics' => $topics]);
    }

    public function getAdd(Request $request) {
        $url = $request->url();
        $topic = new DiscussionTopic;
        $action = $url . '/../store';
        return view("backend/discussion_topic/add", ['topic' => $topic, 'action' => $action]);
    }

    public function postStore(Request $request) {
        $topic = new DiscussionTopic;
        $topic->title_ar = $request->title_ar;
        $topic->title_en = $request->title_en;
        $topic->place = $request->place;
        $topic->save();
        return redirect('/backend/discussiontopic/index');
    }

    public function getEdit($id, Request $request) {
        $url = $request->url();
        $selectTopic = DiscussionTopic::find($id);
        $action = $url . '/../../update';
        return view("backend/discussion_topic/add", ['topic' => $selectTopic, 'action' => $action]);
    }

    public function postUpdate(Request $request) {
        $editTopic = DiscussionTopic::find($request->discussion_topic_id);
        $editTopic->title_ar = $request->title_ar;
        $editTopic->title_en = $request->title_en;
        $editTopic->place = $request->place;
        $editTopic->save();
        return redirect('/backend/discussiontopic/index');
    }

    public function getDestroy($id) {
        $deleteTopic = DiscussionTopic::find($id);
        $deleteTopic->delete();
        return redirect('/backend/discussiontopic/index');
    }

}
