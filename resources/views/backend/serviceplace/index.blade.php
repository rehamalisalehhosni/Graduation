@extends('backend.layouts.app')

@section('content')
  @include('backend.serviceplace.search_form')
<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>cover image</th>
       <th>title</th>
       <th>Main Category</th>
       <th>Sub Main Category</th>
       <th>Actions</th>
     </tr>
   </thead>
   <tbody>
     @foreach ($serviceplace as $service)
     <tr >
       <td><img src= "{{ Request::root() }}{{ $image_path }}{{ $service->cover_image }} " class="img-responsive" /></td>
       <td> {{ $service -> title }}</td>
       <td>{{ $service -> mainCat -> title_en}} </td>
       <td>{{ $service -> subMainCat -> title_en}} </td>
       <td>
         <a href="{{ Request::root() }}/backend/serviceplace/show/{{ $service->service_place_id }}" class="btn btn-success" role="button" > View </a>
         <a href="{{ Request::root() }}/backend/serviceplace/reports/{{ $service->service_place_id }}" class="btn btn-info" role="button" > Reports </a>
         <a href="{{ Request::root() }}/backend/serviceplace/reviews/{{ $service->service_place_id }}" class="btn btn-primary" role="button" > Reviews </a>
         @if ($service->is_approved == 0)
         <a href="{{ Request::root() }}/backend/serviceplace/approve/{{ $service->service_place_id }}" class="btn btn-success" role="button" > approve </a>
         @endif
       </td>
     </tr>
     @endforeach
   </tbody>
 </table>
 <?php echo $serviceplace->render(); ?>

@stop
