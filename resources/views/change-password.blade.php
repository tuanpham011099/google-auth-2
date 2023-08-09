<p>
    If this is your <strong>first time change password</strong>, you can leave "Old password" field empty
</p>
<div>
@if(session('error'))
<div style="color: red;">{{session('error')}}</div>
@endif
@if(session('success'))
<div style="color: greenyellow;">{{session('success')}}</div>
@endif
    <form action="{{url('/change-password')}}" method="post">
        @csrf
        <input type="text" hidden value="{{ csrf_token() }}">
        <label for="old password">Old password</label>
        <input type="password" name="old_password" style="display: block;">
        <label for="password">New password</label>
        <input type="password" name="password" style="display: block;">
        <label for="password-confirm">Re-enter new password</label>
        <input type="password" name="password_confirm" style="display: block;">
        <button type="submit">Submit</button>
    </form>
    <div style="display: block;margin-top: 10px;">
        <a href="/profile">Back</a>
    </div>
</div>
