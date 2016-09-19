@extends('backend.layouts.app')

@section('content')
<a href="{{ Request::root() }}/backend/serviceplacecategory/add" class="btn btn-success" role="button" > Add  New</a>
<p class="clearfix"></p>
<br/>

<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>image</th>
       <th>title ar</th>
       <th>title en</th>
       <th>Parent</th>
       <th>Actions</th>
     </tr>
   </thead>
   <tbody>
     @foreach ( $category as $unit)
     <tr >
       <td><img src= "{{ Request::root() }}{{ $image_path }}/{{ $unit->logo }} " class="img-responsive" /></td>
       <td> {{ $unit->title_ar }}</td>
       <td> {{ $unit->title_en }}</td>
       <td>{{ $unit->serviceMainCategory_child['title_en']}} </td>
       <td>
         <a href="{{ Request::root() }}/backend/serviceplacecategory/edit/{{ $unit->service_main_category_Id }}" class="btn btn-success" role="button" > Edit </a>
         <a href="{{ Request::root() }}/backend/serviceplacecategory/destroy/{{ $unit->service_main_category_Id }}" class="btn btn-danger" role="button" > Delete </a>
       </td>
     </tr>
     @endforeach
   </tbody>
 </table>
 @show

 @endsection
