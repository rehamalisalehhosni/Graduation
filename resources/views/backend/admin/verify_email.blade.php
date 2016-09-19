@extends('landingPage.app')

@section('content')

<div class="row">

  <br/>
  <br/>
  <div class="col-md-6  col-md-offset-3">
<form action="{{ url('/user/verifymail') }}" method="post" >
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="text" class="form-control"  name="email">
    </div>
    <div class="form-group">
        <label for="email">Password:</label>
        <input type="password" class="form-control"  name="password" >
    </div>
    <div class="form-group">
        <label for="email">Verification Code:</label>
        <input type="text" class="form-control"  name="verify_email">
    </div>
    <button type="submit" class="btn btn-primary">Active User</button>
</form>
</div>
</div>
@show

@endsection
