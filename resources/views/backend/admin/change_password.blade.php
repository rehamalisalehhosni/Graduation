<form action="{{ url('/user/forgetpassword') }}" method="post" >
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="text" class="form-control"  name="email">
    </div>
    <div class="form-group">
        <label for="email">Code:</label>
        <input type="text" class="form-control"  name="code">
    </div>
    <div class="form-group">
        <label for="email">Password:</label>
        <input type="password" class="form-control"  name="password">
    </div>
    <div class="form-group">
        <label for="email">Confirm Password:</label>
        <input type="password" class="form-control"  name="password_confirmation">
    </div>
    <button type="submit" class="btn btn-primary">Send</button>
</form>