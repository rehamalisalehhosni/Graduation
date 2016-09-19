@extends('backend.layouts.app')

@section('content')
@if(Session::has('user'))
<div><h1>{{ Session::get('user')[0]['user_name']}}</h1></div>
   @endif

@endsection
