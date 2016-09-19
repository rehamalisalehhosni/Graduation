@extends('backend.layouts.app')

@section('content')
<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>user name</th>
       <th>user email</th>
       <th>report reason</th>
     </tr>
   </thead>
   <tbody>
     @foreach ($reports as $report)
     <tr >
       <td>{{ $report->user->first_name }} {{ $report->user->last_name }}</td>
       <td>{{ $report->user->email }} </td>
       <td> {{ $report->reportreason->title_en }}</td>
     </tr>
     @endforeach
   </tbody>
 </table>

@stop
