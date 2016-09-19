@extends('backend.layouts.app')

@section('content')
<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>user name</th>
       <th>user email</th>
       <th>comment</th>
       <th>Actions</th>
       <th>Reports</th>
     </tr>
   </thead>
   <tbody>
     @foreach ($comments as $comment)
     <tr >
       <td>{{ $comment->user->first_name }} {{ $comment->user->last_name }}</td>
       <td>{{ $comment->user->email }} </td>
       <td> {{ $comment->comment }}</td>
       <td>
         <a href="{{ Request::root() }}/backend/realstate/destroycomment/{{ $comment->real_estate_ad_comment_id	 }}" class="btn btn-danger" role="button" > Delete </a>
       </td>
       <td>
         <a href="{{ Request::root() }}/backend/realstate/commentreports/{{ $comment->real_estate_ad_comment_id	 }}" class="btn btn-danger" role="button" > Reports </a>
       </td>
     </tr>
     @endforeach
   </tbody>
 </table>

@stop
