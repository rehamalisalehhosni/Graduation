@extends('landingPage.app')

@section('content')
<div class="row">

    <br/>
    <br/>
  <div class="col-md-6  col-md-offset-3">
<form action="{{ url('/user/forgetpassword') }}" method="get" >
    <div class="form-group">
        <label for="email">Enter your email:</label>
        <input type="text" class="form-control"  name="email">
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
</form>
</div>
</div>
@show

@endsection
