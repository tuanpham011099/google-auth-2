<div>
    @if(session('success'))
    <div style="color: greenyellow;">{{session('success')}}</div>
    @endif
    @if(session('error'))
    <div style="color: red;">{{session('error')}}</div>
    @endif
    <form action="/2fa-enable" method="post">
        @csrf
        <input type="text" hidden value="{{ csrf_token() }}">
        <label for="code">Enter code to enable 2FA sms</label>
        <input type="text" name="code">
        <button type="submit">Verify</button>
    </form>

    <a href="/profile">Back</a>
</div>
