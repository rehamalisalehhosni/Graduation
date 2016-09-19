@extends('backend.layouts.app')

@section('content')
<table class="table table-striped table-bordered  table-hover">
    <thead>
        <tr>
            <th>Title</th>
            <th>Topic</th>
            <th>Author</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($discussions as $discussion) {
            ?>
            <tr >
                <td> <?= $discussion->title ?> </td>
                <td> <?= $discussion->discussionTopic->title_en ?> </td>
                <td> <?= $discussion->user->first_name . " " . $discussion->user->last_name ?> </td>

                <td>
                    <a href="{{ Request::root() }}/backend/discussion/show/{{ $discussion->discussion_id }}" class="btn btn-success" role="button" > View </a>
                    <a href="{{ Request::root() }}/backend/discussion/reports/{{ $discussion->discussion_id }}" class="btn btn-info" role="button" > Reports </a>
                    <a href="{{ Request::root() }}/backend/discussion/display/{{ $discussion->discussion_id }}" class="btn btn-default" role="button" > Display </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php echo $discussions->render(); ?>
@show

@endsection
