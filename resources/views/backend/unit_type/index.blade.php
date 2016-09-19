@extends('backend.layouts.app')

@section('content')
<a href="{{ Request::root() }}/backend/unittype/add" class="btn btn-success" role="button" > Add  New</a>
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
     @foreach ( $unitType as $unit)
     <tr >
       <td> {{ $unit->title_en }}</td>
       <td> {{ $unit->title_ar }}</td>
       <td>
         <a href="{{ Request::root() }}/backend/unittype/edit/{{ $unit->unit_type_id }}" class="btn btn-success" role="button" > Edit </a>
         <a href="{{ Request::root() }}/backend/unittype/destroy/{{ $unit->unit_type_id }}" class="btn btn-danger" role="button" > Delete </a>
       </td>
     </tr>
     @endforeach
   </tbody>
 </table>
 @show

 @endsection
