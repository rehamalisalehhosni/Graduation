@extends('backend.layouts.app')

@section('content')
<table class="table table-striped table-bordered  table-hover">
   <thead>
     <tr>
       <th>Reporter</th>
       <th>Reporter Email</th>
       <th>Report Reason</th>
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
<?php echo $reports->render(); ?>
@stop
