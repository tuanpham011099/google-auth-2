
<div >

    @if(session('error'))
    <div style="color: red;">{{session('error')}} </div>
    @endif
    <form action="{{url('/login')}}" method="post">
        @csrf
        <input type="text" hidden value="{{ csrf_token() }}">
        <label for="email">Email</label>
        <input type="email" name="email" style="display: block;">
        <label for="password">Password</label>
        <input type="password" name="password" style="display: block;">

        <button type="submit">Login</button>
        or <br>
        <a  href="{{url('/google')}}">
        <img style="width: 180px;" src="https://onymos.com/wp-content/uploads/2020/10/google-signin-button.png" alt=""></a>
    </form>
</div>
