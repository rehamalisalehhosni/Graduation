@extends('backend.layouts.app')

@section('content')
<a href="{{ Request::root() }}/backend/discussiontopic/add" class="btn btn-success" role="button" > Add  New</a>
<p class="clearfix"></p>
<br/>

<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>title ar</th>
       <th>title en</th>
       <th>place</th>
       <th>Actions</th>
     </tr>
   </thead>
   <tbody>
     @foreach ( $topics as $topic)
     <tr >
       <td> {{ $topic->title_ar }}</td>
       <td> {{ $topic->title_en }}</td>
       <td> {{ $topic->place }}</td>
       <td>
         <a href="{{ Request::root() }}/backend/discussiontopic/edit/{{ $topic->discussion_topic_id }}" class="btn btn-success" role="button" > Edit </a>
         <a href="{{ Request::root() }}/backend/discussiontopic/destroy/{{ $topic->discussion_topic_id }}" class="btn btn-danger" role="button" > Delete </a>
       </td>
     </tr>
     @endforeach
   </tbody>
 </table>
 @show

 @endsection
