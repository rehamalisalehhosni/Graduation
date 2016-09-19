@extends('backend.layouts.app')

@section('content')
<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>user name</th>
       <th>user email</th>
       <th>Review</th>
       <th>Rate</th>
       <th>Actions</th>
       <th>Reports</th>
     </tr>
   </thead>
   <tbody>
     @foreach ($comments as $comment)
     <tr >
       <td>{{ $comment->user->first_name }} {{ $comment->user->last_name }}</td>
       <td>{{ $comment->user->email }} </td>
       <td> {{ $comment->review }}</td>
       <td> {{ $comment->rating }}</td>
       <td>
          @if ($comment->is_hide == 1)
         <a href="{{ Request::root() }}/backend/serviceplace/unhidereview/{{ $comment->service_place_review_id	 }}" class="btn btn-danger" role="button" > un Hide </a>
          @else
            <a href="{{ Request::root() }}/backend/serviceplace/hidereview/{{ $comment->service_place_review_id   }}" class="btn btn-danger" role="button" >  Hide</a>
          @endif
       </td>
       <td>
            <a href="{{ Request::root() }}/backend/serviceplace/reviewsreports/{{ $comment->service_place_review_id   }}" class="btn btn-danger" role="button" >  Reports </a>

       </td>
     </tr>
     @endforeach
   </tbody>
 </table>

@stop
