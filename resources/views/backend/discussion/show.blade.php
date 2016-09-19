@extends('backend.layouts.app')

@section('content')
<p class="clearfix"></p>
<table class="table table-striped table-bordered  table-hover">
    <thead>
        <tr>
            <th class="success">Author</th>
            <td>{{$discussion->user->first_name}} {{$discussion->user->last_name}}</td>
        </tr>
        <tr>
            <th class="success">Author Email</th>
            <td>{{$discussion->user->email}}</td>
        </tr>
        <tr>
            <th class="success">Title</th>
            <td>{{$discussion->title}}</td>
        </tr>
        <tr>
            <th class="success">Details</th>
            <td>{{$discussion->details}}</td>
        </tr>
        <tr>
            <th class="success">Topic</th>
            <td>{{$discussion->discussionTopic->title_en}}</td>
        </tr>
        <tr>
            <th class="success">Images</th>
            <td>
                <?php foreach ($discussion->images as $image) { ?>
                    <img style="display: inline" src= "{{ Request::root() }}{{ $image_path }}{{ $image->image }} " class="img-responsive" />
                <?php } ?>
            </td>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<h3>Comments:</h3>
<table class="table table-striped table-bordered  table-hover">
    <thead>
        <tr>
            <th>Author</th>
            <th>Comment</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($comments as $comment)
        <tr >
            <td>{{ $comment->user->first_name }} {{ $comment->user->last_name }}</td>
            <td> {{ $comment->comment }}</td>
            <td>
                <?php if ($comment->is_hide == 0) { ?>
                    <a href="{{ Request::root() }}/backend/discussioncomment/hide/{{ $comment->discussion_comment_id }}" class="btn btn-danger" role="button" > Hide </a>
                <?php } else { ?>
                    <a href="{{ Request::root() }}/backend/discussioncomment/display/{{ $comment->discussion_comment_id }}" class="btn btn-success" role="button" > Display </a>
                <?php } ?>
                <a href="{{ Request::root() }}/backend/discussioncomment/reports/{{ $comment->discussion_comment_id }}" class="btn btn-success" role="button" > Reports </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<?php echo $comments->render(); ?>

@stop
