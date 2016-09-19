@extends('backend.layouts.app')

@section('content')
<a href="{{ Request::root() }}/backend/neighbarhood/add" class="btn btn-success" role="button" > Add  New</a>
<p class="clearfix"></p>
<br/>

<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>title ar</th>
       <th>title en</th>
       <th>Actions</th>
     </tr>
   </thead>
   <tbody>
     @foreach ( $neighbarhood as $neighbar)
     <tr >
       <td> {{ $neighbar->title_en }}</td>
       <td> {{ $neighbar->title_ar }}</td>
       <td>
         <a href="{{ Request::root() }}/backend/neighbarhood/edit/{{ $neighbar->neighbarhood_id }}" class="btn btn-success" role="button" > Edit </a>
         <a href="{{ Request::root() }}/backend/neighbarhood/destroy/{{ $neighbar->neighbarhood_id }}" class="btn btn-danger" role="button" > Delete </a>
       </td>
     </tr>
     @endforeach
   </tbody>
 </table>
 @show

 @endsection
