@extends('backend.layouts.app')

@section('content')
  @include('backend.realstate.search_form')

<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>cover image</th>
       <th>title</th>
       <th>user name</th>
       <th>user email</th>
       <th>Actions</th>
     </tr>
   </thead>
   <tbody>
     @foreach ($realState as $real)
     <tr >
       <td><img src= "{{ Request::root() }}{{ $image_path }}{{ $real->cover_image }} " class="img-responsive" /></td>
       <td> {{ $real->title }}</td>
       <td>{{ $real->user->first_name }} {{$real->user->last_name }}</td>
       <td>{{ $real->user->email }} </td>
       <td>
         <a href="{{ Request::root() }}/backend/realstate/show/{{ $real->real_estate_ad_id }}" class="btn btn-success" role="button" > View </a>
         <a href="{{ Request::root() }}/backend/realstate/reports/{{ $real->real_estate_ad_id }}" class="btn btn-info" role="button" > Reports </a>
         <a href="{{ Request::root() }}/backend/realstate/comments/{{ $real->real_estate_ad_id }}" class="btn btn-primary" role="button" > Comments </a>
         <a href="{{ Request::root() }}/backend/realstate/destroy/{{ $real->real_estate_ad_id }}" class="btn btn-danger" role="button" > Delete </a>
       </td>
     </tr>
     @endforeach
   </tbody>
 </table>
 <?php echo $realState->appends(['search' => $query ])->render(); ?>
 @show

 @endsection
