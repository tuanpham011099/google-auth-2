<div>
    @if(session('success'))
    <div style="color: greenyellow;">{{session('success')}}</div>
    @endif
    @if(session('error'))
    <div style="color: red;">{{session('error')}}</div>
    @endif
    <form action="/2fa-disable" method="post">
        @csrf
        <input type="text" hidden value="{{ csrf_token() }}">
        <label for="code">Enter password</label>
        <input type="password" name="password">
        <button type="submit">Verify</button>
    </form>

    <a href="/profile">Back</a>
</div>
